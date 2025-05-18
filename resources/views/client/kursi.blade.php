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
                            <button class="pay-button btn btn-block bg-white kursi" data-id="{{ $data['id'] }}"
                                data-kursi="{{ $kursiKode }}" data-encrypted="{{ Crypt::encrypt($data) }}">
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
    @section('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <!-- Midtrans Snap JS -->
     <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

     <script>
         // Payment handling
         document.addEventListener('DOMContentLoaded', function() {
             const payButtons = document.querySelectorAll('.pay-button');
             payButtons.forEach(button => {
                 button.addEventListener('click', function() {
                     const pemesananId = this.getAttribute('data-id');

                     Swal.fire({
                         title: 'Memproses Pembayaran',
                         text: 'Mohon tunggu...',
                         allowOutsideClick: false,
                         didOpen: () => {
                             Swal.showLoading();
                         }
                     });

                     fetch('/midtrans/create-transaction', {
                             method: 'POST',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'Accept': 'application/json',
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                             },
                             body: JSON.stringify({
                                 pemesanan_id: pemesananId
                             })
                         })
                         .then(response => {
                             if (!response.ok) {
                                 throw new Error('Network response was not ok');
                             }
                             return response.json();
                         })
                         .then(data => {
                             if (data.snap_token) {
                                 Swal.close();

                                 window.snap.pay(data.snap_token, {
                                     onSuccess: function(result) {
                                         Swal.fire({
                                             title: 'Success',
                                             text: 'Pembayaran berhasil!',
                                             icon: 'success',
                                             timer: 2000,
                                             timerProgressBar: true,
                                             showConfirmButton: false
                                         }).then(() => location.reload());
                                     },
                                     onPending: function(result) {
                                         Swal.fire({
                                             title: 'Info',
                                             text: 'Pembayaran sedang diproses',
                                             icon: 'info',
                                             timer: 2000,
                                             timerProgressBar: true,
                                             showConfirmButton: false
                                         }).then(() => location.reload());
                                     },
                                     onError: function(result) {
                                         Swal.fire({
                                             title: 'Error',
                                             text: 'Pembayaran gagal!',
                                             icon: 'error',
                                             timer: 2000,
                                             timerProgressBar: true,
                                             showConfirmButton: false
                                         });
                                     },
                                     onClose: function() {
                                         Swal.fire({
                                             title: 'Info',
                                             text: 'Pembayaran dibatalkan',
                                             icon: 'info',
                                             timer: 2000,
                                             timerProgressBar: true,
                                             showConfirmButton: false
                                         });
                                     }
                                 });
                             } else {
                                 throw new Error(data.error || 'Failed to get payment token');
                             }
                         })
                         .catch(error => {
                             console.error('Error:', error);
                             Swal.fire({
                                 title: 'Error',
                                 text: 'Terjadi kesalahan: ' + error.message,
                                 icon: 'error',
                                 confirmButtonText: 'OK'
                             });
                         });
                 });
             });
         });
     </script>
@endsection

@endsection

