<?php
namespace App\Http\Controllers\Api;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rute;
use Illuminate\Support\Facades\Crypt;

class RuteApiController extends Controller
{
    public function search(Request $request)
{
    $request->validate([
        'start' => 'required',
        'end' => 'required',
        'category_id' => 'required',
        'waktu' => 'required|date',
    ]);

    $rute = Rute::with(['transportasi.category'])
        ->where('start', $request->start)
        ->where('end', $request->end)
        ->whereHas('transportasi', function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        })
        ->get();

    $hasil = [];

    foreach ($rute as $val) {
        $jumlahPemesanan = Pemesanan::where('rute_id', $val->id)
            ->whereDate('waktu', $request->waktu)
            ->count();

        $jumlahKursi = $val->transportasi->jumlah ?? 0;
        $tersedia = $jumlahKursi - $jumlahPemesanan;

        $hasil[] = [
            'id' => $val->id,
            'start' => $val->start,
            'end' => $val->end,
            'tujuan' => $val->tujuan,
            'harga' => $val->harga,
            'jam' => $val->jam,
            'waktu' => $request->waktu,
            'kode_transportasi' => $val->transportasi->kode,
            'nama_transportasi' => $val->transportasi->name,
            'kategori' => $val->transportasi->category->name,
            'kursi_tersedia' => $tersedia,
        ];
    }

    return response()->json([
        'status' => true,
        'message' => 'Data rute ditemukan',
        'data' => $hasil
    ]);
}



    public function dropdownOptions()
    {
        $category = DB::table('category')->pluck('name'); // ['Ekonomi', 'Bisnis', ...]
        $start = DB::table('rute')->distinct()->pluck('start'); // ['Jakarta', 'Bandung', ...]
        $end = DB::table('rute')->distinct()->pluck('end');     // ['Yogyakarta', 'Semarang', ...]

        return response()->json([
            'categories' => $category,
            'start_routes' => $start,
            'end_routes' => $end,
        ]);
    }
}
