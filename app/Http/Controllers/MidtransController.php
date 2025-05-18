<?php
namespace App\Http\Controllers;

use App\Http\Middleware\Penumpang;
use App\Mail\PembayaranBerhasilMail;
use App\Models\Detail_pemesanan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Tambahkan untuk enkripsi

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction(Request $request)
{
    try {
        $pemesanan = Pemesanan::with(['rute', 'penumpang'])->find($request->pemesanan_id);

        if (! $pemesanan) {
            return response()->json(['error' => 'Pemesanan tidak ditemukan'], 404);
        }

        $rute       = $pemesanan->rute;
        $penumpang  = $pemesanan->penumpang;

        $transactionDetails = [
            'transaction_details' => [
                'order_id'     => 'ORDER-' . time() . '-' . $pemesanan->id,
                'gross_amount' => (int) $pemesanan->total,
            ],
            'customer_details' => [
                'first_name' => $penumpang->nama ?? 'Penumpang',
            ],
            'item_details' => [
                [
                    'id'       => 1,
                    'price'    => (int) $rute->harga ?? 0,
                    'name'     => 'bandung-jawa',
                    'quantity' => 1,
                ],
            ],
        ];

        $snapToken = Snap::getSnapToken($transactionDetails);

        return response()->json([
            'snap_token' => $snapToken,
            'message'    => 'Berhasil membuat transaksi',
        ]);

    } catch (\Exception $e) {
        Log::error('Midtrans Error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function paymentCallback(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK RECEIVED ===');
        Log::info('Request Data:', $request->all());

        try {
            $orderId           = $request->order_id ?? '';
            $transactionStatus = $request->transaction_status ?? '';
            $transactionTime   = $request->transaction_time ?? now();

            if (empty($orderId)) {
                return response()->json(['status' => 'error', 'message' => 'Order ID kosong'], 200);
            }

            $orderParts = explode('-', $orderId);
            if (count($orderParts) < 3 || ! is_numeric($orderParts[2])) {
                return response()->json(['status' => 'error', 'message' => 'Format order ID tidak valid'], 200);
            }

            $pemesananId = (int) $orderParts[2];

            DB::beginTransaction();

            $pemesanan = Pemesanan::lockForUpdate()->find($pemesananId);
            if (! $pemesanan) {
                return response()->json(['status' => 'error', 'message' => 'Pemesanan tidak ditemukan'], 404);
            };

            $statusPembayaran = match ($transactionStatus) {
                'capture', 'settlement' => 'sudah_bayar',
                'pending'               => 'pending',
                default                 => 'gagal',
            };

            $newStatus = match ($transactionStatus) {
                'capture', 'settlement' => 'selesai',
                'pending'               => 'proses',
                default                 => 'batal',
            };

            $pemesanan->status = $newStatus;
            $pemesanan->save();

            $pembayaran = Pembayaran::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'pemesanan_id'       => $pemesananId,
                    'status'             => $statusPembayaran,
                    'metode_pembayaran'  => $request->payment_type ?? 'unknown',
                    'tanggal_pembayaran' => $transactionTime,
                    'updated_at'         => now(),
                ]
            );


            DB::commit();

            return response()->json([
                'status'            => 'success',
                'message'           => 'Callback berhasil',
                'pemesanan_status'  => $pemesanan->status,
                'pembayaran_status' => $pembayaran->status,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Callback gagal'], 500);
        }
    }


}
