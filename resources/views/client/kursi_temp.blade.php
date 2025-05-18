@extends('layouts.app')
@section('title', 'Cari Kursi')

@section('styles')
    <style>
        a:hover {
            text-decoration: none;
        }

        .kursi {
            box-sizing: border-box;
            border: 2px solid #858796;
            width: 100%;
            height: 120px;
            display: flex;
        }

        .disabled-kursi {
            background: #858796 !important;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12" style="margin-top: -15px">
            <a href="javascript:window.history.back();" class="text-white btn">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>

            <div class="row mt-2">
                @for ($i = 1; $i <= $transportasi->jumlah; $i++)
                    @php
                        $kursiKode = 'K' . $i;
                        $isBooked = in_array($kursiKode, $pemesanan);
                    @endphp

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                        @if (!$isBooked)
                            <button class="pay-button btn btn-block bg-white kursi"
                                data-id="{{ $data['id'] }}"
                                data-kursi="{{ $kursiKode }}"
                                data-encrypted="{{ Crypt::encrypt($data) }}">
                                <div class="font-weight-bold text-primary m-auto" style="font-size: 26px;">
                                    {{ $kursiKode }}
                                </div>
                            </button>
                        @else
                            <div class="kursi disabled-kursi">
                                <div class="font-weight-bold text-white m-auto" style="font-size: 26px;">
                                    {{ $kursiKode }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Midtrans Snap JS -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButtons = document.querySelectorAll('.pay-button');

            payButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const pemesananId = this.getAttribute('data-id');
                    const loadingSwal = Swal.fire({
                        title: 'Memproses Pembayaran',
                        html: 'Sedang mempersiapkan pembayaran...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('{{ route("midtrans.create") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            pemesanan_id: pemesananId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        loadingSwal.close();

                        if (!data.snap_token) {
                            throw new Error(data.error || 'Token pembayaran tidak tersedia');
                        }

                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                Swal.fire({
                                    title: 'Pembayaran Berhasil',
                                    text: 'Transaksi Anda telah berhasil diproses',
                                    icon: 'success'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            onPending: function(result) {
                                Swal.fire({
                                    title: 'Menunggu Pembayaran',
                                    text: 'Silahkan selesaikan pembayaran Anda',
                                    icon: 'info'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            onError: function(result) {
                                Swal.fire({
                                    title: 'Pembayaran Gagal',
                                    text: 'Terjadi kesalahan saat memproses pembayaran',
                                    icon: 'error'
                                });
                            },
                            onClose: function() {
                                Swal.fire({
                                    title: 'Pembayaran Dibatalkan',
                                    text: 'Anda menutup halaman pembayaran',
                                    icon: 'info'
                                });
                            }
                        });
                    })
                    .catch(error => {
                        loadingSwal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: error.message || 'Terjadi kesalahan saat memproses pembayaran',
                            icon: 'error'
                        });
                    });
                });
            });
        });
    </script>
@endsection
