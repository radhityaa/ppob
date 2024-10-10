@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div>
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                <div class="card invoice-preview-card">
                    <div class="card-body">
                        <div
                            class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                            <div class="mb-xl-0 mb-4">
                                <div class="d-flex svg-illustration align-items-center mb-4 gap-2">
                                    <div class="app-brand-logo demo">
                                        <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                                fill="#7367F0" />
                                            <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                                fill="#161616" />
                                            <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                                fill="#161616" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                                fill="#7367F0" />
                                        </svg>
                                    </div>
                                    <span class="app-brand-text fw-bold fs-4"> {{ config('app.name') }} </span>
                                </div>
                                <p class="mb-2">{{ env('WEBSITE_ADDRESS') }}</p>
                                <p class="mb-0">{{ env('WEBSITE_PHONE') }}</p>
                            </div>
                            <div>
                                <h4 class="fw-medium mb-2">{{ $deposit->invoice }}</h4>
                                <div class="pt-1">
                                    <span>Tanggal:</span>
                                    <span class="fw-medium">{{ $deposit->created_at->format('d M Y h:i:s') }}</span>
                                </div>
                                <div class="mb-2">
                                    <span>Tanggal Exp:</span>
                                    <span
                                        class="fw-medium text-danger">{{ $deposit->expired_at->format('d M Y h:i:s') }}</span>
                                </div>
                                <div class="mb-2 pt-1">
                                    <span class="fw-medium fs-4 text-uppercase"><span
                                            class="badge @if ($deposit->status === 'paid') bg-success @elseif ($deposit->status === 'unpaid') bg-dark @else bg-danger @endif">{{ $deposit->status }}</span></span>
                                </div>
                                @if ($deposit->paid_at)
                                    <div class="mb-2 pt-1">
                                        <span>Dibayar Pada:</span>
                                        <span
                                            class="fw-medium text-success">{{ $deposit->paid_at->format('d M Y h:i:s') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" />
                    <div class="card-body">
                        <div class="row p-sm-3 p-0">
                            <div class="col-xl-6 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                                <h6 class="mb-3">Ditagih Ke:</h6>
                                <p class="mb-1">{{ $deposit->user->name }}</p>
                                <p class="mb-1">{{ $deposit->user->username }}</p>
                                <p class="mb-1">{{ $deposit->user->phone }}</p>
                                <p class="mb-1">{{ $deposit->user->email }}</p>
                            </div>
                            <div class="col-xl-6 col-md-12 col-sm-7 col-12">
                                <h6 class="mb-4">Pembayaran:</h6>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="pe-4">Invoice:</td>
                                            <td class="fw-medium">{{ $deposit->invoice }}</td>
                                        </tr>
                                        <tr>
                                            <td class="pe-4">Bank:</td>
                                            <td>{{ $deposit->method }}</td>
                                        </tr>
                                        @php
                                            use Illuminate\Support\Str;
                                        @endphp
                                        @if (Str::startsWith($deposit->invoice, 'DPSM'))
                                            <tr>
                                                <td class="pe-4">Payment:</td>
                                                <td><span>Manual</span> </td>
                                            </tr>
                                        @elseif ($deposit->pay_code)
                                            <tr>
                                                <td class="pe-4">Code:</td>
                                                <td><span id="pay-code">{{ $deposit->pay_code }}</span> <span
                                                        style="color: green; font-weight: 200; cursor: pointer;"
                                                        id="copy-code">(Copy)</span>
                                                </td>
                                            </tr>
                                        @elseif($deposit->pay_url)
                                            <tr>
                                                <td class="pe-4">Payment:</td>
                                                <td><span>{{ $deposit->pay_url }}</span> <a href="{{ $deposit->pay_url }}"
                                                        target="_blank"
                                                        style="color: green; font-weight: 200; cursor: pointer;">(Open)</a>
                                                </td>
                                            </tr>
                                        @elseif(!$deposit->pay_url && !$deposit->pay_code)
                                            <tr>
                                                <td class="pe-4">Payment:</td>
                                                <td><span>{{ $deposit->checkout_url }}</span> <a
                                                        href="{{ $deposit->checkout_url }}" target="_blank"
                                                        style="color: green; font-weight: 200; cursor: pointer;">(Open)</a>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive border-top">
                        <table class="m-0 table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Keterangan</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-nowrap">Deposit Saldo Rp
                                        {{ number_format($deposit->nominal, 0, '.', '.') }}</td>
                                    <td class="text-nowrap">Pembayaran {{ $deposit->method }}</td>
                                    <td>Rp {{ number_format($deposit->nominal, 0, '.', '.') }}</td>
                                    <td>1</td>
                                    <td>Rp {{ number_format($deposit->nominal, 0, '.', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end">
                        <div class="row px-5">
                            <table class="m-0 mt-3">
                                <tr>
                                    <td class="px-4">Subtotal: </td>
                                    <td class="fw-medium">Rp {{ number_format($deposit->nominal, 0, '.', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4">Diskon: </td>
                                    <td class="fw-medium">Rp 0</td>
                                </tr>
                                <tr>
                                    <td class="px-4">Fee: </td>
                                    <td class="fw-medium">Rp {{ number_format($deposit->fee, 0, '.', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4">Total: </td>
                                    <td class="fw-medium">Rp {{ number_format($deposit->total, 0, '.', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4">Terbilang: </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="fw-bold px-4 text-end" style="text-transform: capitalize">{{ $terbilang }} Rupiah</div>

                    <div class="card-body mx-3">
                        <div class="row">
                            <div class="col-12">
                                <span class="fw-medium">Note:</span>
                                <span>Permintaan deposit harus dibayar pada hari yang sama dengan penerimaan faktur. Jika
                                    setoran tidak dibayarkan pada hari yang sama,
                                    permintaan akan dibatalkan secara otomatis oleh sistem.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice -->

            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card">
                    <div class="card-body">
                        @role('admin')
                            @if ($deposit->status === 'unpaid')
                                <button class="btn btn-primary d-grid w-100 mb-2" id="konfirmasi">
                                    <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                            class="ti ti-check ti-xs me-2"></i>Konfirmasi</span>
                                </button>
                            @endif
                        @endrole
                        {{-- <button class="btn btn-info d-grid w-100 mb-2">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                    class="ti ti-send ti-xs me-2"></i>Kirim Invoice</span>
                        </button> --}}
                        <form action="{{ route('deposit.print') }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice" id="invoice" value="{{ $deposit->invoice }}">

                            <button type="submit" class="btn btn-success d-grid w-100 mb-2">
                                <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                        class="ti ti-printer ti-xs me-2"></i>Print</span>
                            </button>
                        </form>
                        @if ($deposit->status === 'unpaid')
                            <button class="btn btn-danger d-grid w-100 mb-2" id="cancel">
                                <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                        class="ti ti-x ti-xs me-2"></i>Batalkan</span>
                            </button>
                        @endif
                        <a href="{{ route('deposit.index') }}" class="btn btn-warning d-grid w-100 mb-2">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                    class="ti ti-chevrons-left ti-xs me-2"></i>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {
            $('#copy-code-btn').on('click', function() {
                // Dapatkan elemen yang berisi kode yang akan disalin
                var codeElement = $('#pay-code').text();

                // Buat elemen textarea sementara untuk menyalin teks
                var $tempInput = $('<textarea>');
                $tempInput.val(codeElement).appendTo('body').select();

                // Salin teks ke clipboard
                document.execCommand('copy');

                // Hapus elemen textarea sementara
                $tempInput.remove();

                // Opsional: Tampilkan pesan konfirmasi atau ubah tampilan tombol
                alert('Code Berhasil Dicopy!');
            });

            $('#copy-code').on('click', function() {
                // Dapatkan elemen yang berisi kode yang akan disalin
                var codeElement = $('#pay-code').text();

                // Buat elemen textarea sementara untuk menyalin teks
                var $tempInput = $('<textarea>');
                $tempInput.val(codeElement).appendTo('body').select();

                // Salin teks ke clipboard
                document.execCommand('copy');

                // Hapus elemen textarea sementara
                $tempInput.remove();

                // Opsional: Tampilkan pesan konfirmasi atau ubah tampilan tombol
                alert('Code Berhasil Dicopy!');
            });
        });

        $('#konfirmasi').on('click', function() {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                customClass: {
                    confirmButton: 'd-none'
                },
                buttonsStyling: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            let url = "{{ route('deposit.confirm', ':invoice') }}"
            url = url.replace(':invoice', '{{ $deposit->invoice }}')

            $.ajax({
                url: url,
                method: "POST",
                dataType: "json",
                success: function(res) {
                    window.location.reload()
                    Swal.close()
                    Swal.fire({
                        title: 'Success!',
                        text: 'Konfirmasi Berhasil.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: 'd-none'
                        },
                        buttonsStyling: false,
                    })
                },
                error: function(err) {
                    window.location.reload()
                    Swal.close()
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while fetching data.',
                        icon: 'error',
                        timer: 1500,
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: 'd-none'
                        },
                        buttonsStyling: false,
                    })
                }
            })
        })

        $('#cancel').on('click', function() {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak akan dapat mengembalikan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('deposit.cancel', ':invoice') }}";
                    url = url.replace(':invoice', '{{ $deposit->invoice }}');

                    $.ajax({
                        url: url,
                        method: "POST",
                        success: function(res) {
                            console.log(res)
                            Swal.fire({
                                title: 'Berhasil',
                                text: res.message,
                                icon: 'success',
                                showCancelButton: false,
                                timer: 2500,
                                customClass: {
                                    confirmButton: 'd-none',
                                    cancelButton: 'd-none'

                                },
                                buttonsStyling: false,
                            })
                            window.location.reload();
                        },
                        error: function(err) {
                            Swal.fire({
                                title: 'Gagal',
                                text: err.responseJSON.message,
                                icon: 'error',
                                showCancelButton: false,
                                customClass: {
                                    confirmButton: 'd-none',
                                    cancelButton: 'd-none'

                                },
                                buttonsStyling: false,
                            })
                        }
                    })
                }
            })
        })
    </script>
@endpush
