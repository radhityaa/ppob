@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />

    <style>
        .copy-text {
            text-transform: capitalize;
            color: blue;
            cursor: pointer;
        }

        .copy-text:hover {
            text-decoration: underline;
        }

        .copy-text-detail {
            text-transform: capitalize;
            color: blue;
            cursor: pointer;
        }

        .copy-text-detail:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            </div>
        </div>
    </div>

    <!-- List Widget -->
    <div class="card mb-4">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-sm-0 pb-3">
                            <div>
                                <h3 class="mb-1">{{ $total }}</h3>
                                <p class="mb-0">Total</p>
                            </div>
                            <span class="avatar me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti ti-list ti-md"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-4" />
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-sm-0 pb-3">
                            <div>
                                <h3 class="mb-1">{{ $totalPending }}</h3>
                                <p class="mb-0">Pending</p>
                            </div>
                            <span class="avatar me-lg-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti ti-hourglass-empty ti-md"></i></span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none" />
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start border-end pb-sm-0 card-widget-3 pb-3">
                            <div>
                                <h3 class="mb-1">{{ $totalSukses }}</h3>
                                <p class="mb-0">Sukses</p>
                            </div>
                            <span class="avatar me-sm-4">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti ti-checks ti-md"></i></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="mb-1">{{ $totalGagal }}</h3>
                                <p class="mb-0">Gagal</p>
                            </div>
                            <span class="avatar">
                                <span class="avatar-initial bg-label-secondary rounded"><i
                                        class="ti ti-circle-off ti-md"></i></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <form action="{{ route('history.prabayar') }}" method="GET" class="row">
                    {{-- <div class="py-2 col-md-4">
                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', $startDate) }}">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date', $endDate) }}">
                        </div>
                    </div> --}}
                    <div class="col-md-2 py-2">
                        <select id="status" name="status" class="form-select">
                            <option value="" selected disabled>-- Pilih Status --</option>
                            <option value="" {{ old('status', $filterStatus) == 'Semua' ? 'selected' : '' }}>
                                Semua
                            </option>
                            <option value="Pending" {{ old('status', $filterStatus) == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="Sukses" {{ old('status', $filterStatus) == 'Sukses' ? 'selected' : '' }}>Sukses
                            </option>
                            <option value="Gagal" {{ old('status', $filterStatus) == 'Gagal' ? 'selected' : '' }}>Gagal
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 py-2">
                        <input type="text" id="invoice" name="invoice" class="form-control" placeholder="TRX-xxxxxxxx"
                            value="{{ old('invoice', $filterInvoice) }}">
                    </div>
                    <div class="col-md-2 py-2">
                        <button type="submit" class="btn btn-primary btn-block w-100">Cari</button>
                    </div>
                    <div class="col-md-2 py-2">
                        <a href="{{ route('history.prabayar') }}" class="btn btn-light btn-block w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Card --}}
    <div class="my-2">
        <div class="row g-4">
            @foreach ($data as $item)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <span
                                    class="badge {{ $item->status === 'Sukses' ? 'bg-label-success' : ($item->status === 'Pending' ? 'bg-label-warning' : 'bg-label-danger') }}">{{ $item->status }}</span>
                                <div class="d-flex justify-content-center">
                                    <sup
                                        class="h6 fw-medium pricing-currency {{ $item->status === 'Sukses' ? 'text-success' : ($item->status === 'Pending' ? 'text-warning' : 'text-danger') }} mb-0 me-1 mt-3">Rp</sup>
                                    <h1
                                        class="{{ $item->status === 'Sukses' ? 'text-success' : ($item->status === 'Pending' ? 'text-warning' : 'text-danger') }} mb-0">
                                        {{ number_format($item->price, 0, '.', '.') }}</h1>
                                </div>
                            </div>
                            <div class="g-2 ps-1">
                                <span
                                    class="d-block text-truncate-multiline">{{ $item->created_at->format('d/m/Y H:i:s') }}</span>
                                <span class="d-block text-truncate-multiline">Invoice: <span
                                        class="fw-bold">{{ $item->invoice }}</span></span>
                                <span class="d-block text-truncate-multiline mt-3">Produk:
                                    <span class="fw-bold">{{ $item->product_name }}</span></span>
                                <span class="d-block text-truncate-multiline">Tujuan: <span
                                        class="fw-bold">{{ $item->target }}</span></span>
                                <span class="d-block text-truncate-multiline">Ket: {{ $item->message }}</span>
                                <span class="d-block text-truncate-multiline mt-3">Serial Number:</span>
                                <span class="d-block text-truncate-multiline fw-bold">{{ $item->sn }}</span>
                            </div>
                            <div class="row mt-2">
                                <div class="col-4 p-1">
                                    <button class="w-100 btn bg-primary" id="share"
                                        data-invoice="{{ $item->invoice }}" data-target="{{ $item->target }}">
                                        <span class="text-white">Share</span>
                                    </button>
                                </div>
                                <div class="col-4 p-1">
                                    <button class="w-100 btn bg-warning" id="print"
                                        data-invoice="{{ $item->invoice }}" data-target="{{ $item->target }}">
                                        <span class="text-white">Print</span>
                                    </button>
                                </div>
                                {{-- <div class="col-4 p-1">
                                    <button class="w-100 btn bg-success" id="share" data-invoice="{{ $item->invoice }}"
                                        data-target="{{ $item->target }}">
                                        <span class="text-white">Whatsapp</span>
                                    </button>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <!-- Previous Page Link -->
                    <li class="page-item {{ $data->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $data->previousPageUrl()
                                ? $data->appends([
                                        'status' => $filterStatus,
                                        'invoice' => $filterInvoice,
                                    ])->previousPageUrl()
                                : '#' }}">
                            <i class="ti ti-chevron-left ti-xs"></i>
                        </a>
                    </li>

                    <!-- First Page Link -->
                    <li class="page-item {{ $data->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $data->appends([
                                    'status' => $filterStatus,
                                    'invoice' => $filterInvoice,
                                ])->url(1) }}">
                            <i class="ti ti-chevrons-left ti-xs"></i>
                        </a>
                    </li>

                    <!-- Pagination Numbers -->
                    @for ($i = max(1, $data->currentPage() - 2); $i <= min($data->lastPage(), $data->currentPage() + 2); $i++)
                        <li class="page-item {{ $data->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link"
                                href="{{ $data->appends([
                                        'status' => $filterStatus,
                                        'invoice' => $filterInvoice,
                                    ])->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    <!-- Next Page Link -->
                    <li class="page-item {{ $data->currentPage() == $data->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $data->nextPageUrl()
                                ? $data->appends([
                                        'status' => $filterStatus,
                                        'invoice' => $filterInvoice,
                                    ])->nextPageUrl()
                                : '#' }}">
                            <i class="ti ti-chevron-right ti-xs"></i>
                        </a>
                    </li>

                    <!-- Last Page Link -->
                    <li class="page-item {{ $data->currentPage() == $data->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link"
                            href="{{ $data->appends([
                                    'status' => $filterStatus,
                                    'invoice' => $filterInvoice,
                                ])->url($data->lastPage()) }}">
                            <i class="ti ti-chevrons-right ti-xs"></i>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>

    <!-- Modal -->
    <div class="mt-3">
        <div class="modal fade modal-lg" id="modalDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailTitle">Detail Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table">
                            <thead>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <td class="d-none" id="buyer_sku_code"></td>
                                <tr>
                                    <th class="fw-semibold">Tanggal</th>
                                    <td class="fw-bold" id="date"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Tujuan</th>
                                    <td id="target" class="fw-bold"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Produk</th>
                                    <td id="product_name"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">SN</th>
                                    <td id="sn"><span id="sn-text" class="fw-bold"></span> <span
                                            class="copy-text-detail" data-sn="" id="copy-sn-detail">(copy)</span>
                                    </td>
                                </tr>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Ket</th>
                                    <td id="message"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Harga</th>
                                    <td id="price" class="fw-bold"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Status</th>
                                    <td id="status"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Margin Share --}}
    <div class="mt-3">
        <div class="modal fade modal-sm" id="modalMarginShare" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <input type="hidden" name="invoice" id="invoice">
                        <div class="mb-3">
                            <label for="margin" class="form-label">Harga Jual</label>
                            <input type="text" name="margin" id="margin" class="form-control" required>
                        </div>

                        @if ($waStatus)
                            <div class="mb-3">
                                <label for="receiver" class="form-label">Nomor Pelangan</label>
                                <input type="number" name="receiver" id="receiver" class="form-control"
                                    placeholder="08123456789" required>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <button type="button" id="btn-share-wa" class="w-100 btn btn-sm btn-success"><i
                                        class="ti ti-brand-whatsapp me-1"></i>
                                    Whatsapp</button>
                            @else
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" id="btn-share-wa" class="w-100 btn btn-sm btn-success"><i
                                            class="ti ti-eye me-1"></i>
                                        Detail</button>
                        @endif
                        <x-button-loading />
                        {{-- <button type="button" id="btn-share-telegram" class="w-100 btn btn-sm btn-primary"><i
                                    class="ti ti-brand-telegram me-1"></i>
                                Telegram</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- Modal Margin Print --}}
    <div class="mt-3">
        <div class="modal fade modal-sm" id="modalMarginPrint" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{ route('history.prabayar.print') }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice" id="invoice">
                            <div class="mb-3">
                                <label for="margin" class="form-label">Harga Jual</label>
                                <input type="text" name="margin" id="margin" class="form-control margin-print"
                                    required>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="w-100 btn btn-warning"><i class="ti ti-printer me-1"></i>
                                    Print</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script script src="{{ asset('assets/js/socket.io.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        function numberFormatIdr(value) {
            var reverse = value.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + formatted;
        }

        function refresh() {
            table.ajax.reload(null, false)
        }

        $('body').on('click', '#share', function() {
            $('#modalMarginShare').modal('show')
            var invoice = $(this).data('invoice')
            $('#invoice').val(invoice)
            $('#receiver').val($(this).data('target'))
        })

        $('body').on('click', '#btn-share-wa', function() {
            var invoice = $('#invoice').val()
            var margin = $('#margin').val()
            var receiver = $('#receiver').val()
            var waStatus = "{{ $waStatus }}"

            if (waStatus === "1") {
                // Validasi format receiver/target
                var regex = /^(08|62)\d+$/;
                if (!regex.test(receiver)) {
                    Swal.fire({
                        title: 'Nomor Pelangan Salah',
                        text: 'Masukkan nomor yang valid di awali 08 atau 62',
                        icon: 'error',
                        confirmButtonText: 'Oke',
                        showConfirmButton: true
                    })
                    return;
                }
            }

            if (!margin) {
                Swal.fire({
                    title: 'Harga Jual Harus Diisi',
                    text: 'Isi kolom Harga Jual',
                    icon: 'error',
                    confirmButtonText: 'Oke',
                    showConfirmButton: true
                })
                return;
            }

            let url = "{{ route('history.prabayar.detail', ':invoice') }}"
            url = url.replace(':invoice', invoice)

            $('.btn-loading').removeClass('d-none')
            $('#btn-share-wa').addClass('d-none')

            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('#modalDetail').modal('show')
                    $('#modalMarginShare').modal('hide')

                    $.ajax({
                        url: "{!! route('history.prabayar.send.invoice') !!}",
                        method: "POST",
                        data: {
                            invoice: invoice,
                            margin: margin,
                            receiver: receiver
                        },
                        success: function(res) {
                            if (waStatus === "1") {
                                Swal.fire({
                                    title: 'Berhasil',
                                    text: 'Invoice telah dikirim ke nomor pelanggan',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    customClass: {
                                        confirmButton: 'd-none'
                                    },
                                    buttonsStyling: false,
                                })
                            }
                        }
                    })

                    $('#modalDetailTitle').html(res.invoice)
                    $('#date').html(res.created_at)
                    $('#target').html(res.target)
                    $('#product_name').html(res.product_name)
                    $('#sn-text').html(res.sn);
                    $('#copy-sn-detail').data('sn', res.sn); // Set SN data attribute for copy
                    $('#message').html(res.message)
                    $('#price').html(margin)

                    if (res.status === 'Sukses') {
                        $('td#status').html('<span class="badge bg-success">Sukses</span>')
                    } else if (res.status === 'Pending') {
                        $('td#status').html('<span class="badge bg-warning">Pending</span>')
                    } else {
                        $('td#status').html('<span class="badge bg-danger">Gagal</span>')
                    }

                },
                error: function(err) {
                    $('#modalDetailTitle').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#date').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#target').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#product_name').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#sn-text').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#message').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#price').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#status').html('Terjadi Kesalahan, Ulangi Lagi')
                }
            })
        })

        $('body').on('click', '#print', function() {
            var invoice = $(this).data('invoice')
            $('#modalMarginPrint').modal('show')
            $('input#invoice').val(invoice)
        })

        function numberFormatIdr(value) {
            var reverse = value.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + formatted;
        }

        $(document).ready(function() {
            $('body').on('click', '.copy-text', function() {
                var snText = $(this).closest('.copy-sn').data('sn')
                navigator.clipboard.writeText(snText).then(function() {
                    alert('Berhasil Dicopy: ' + snText)
                }, function(err) {
                    alert('Gagal Dicopy: ' + err.responseJSON.message)
                })
            })

            $('#margin').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#margin').val(formatted);
            })

            $('.margin-print').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('.margin-print').val(formatted);
            })

            $('#modalMarginPrint').on('hidden.bs.modal', function() {
                $('#margin').val('')
                $('.modal-body form')[0].reset();
            })

            $('#modalMarginShare').on('hidden.bs.modal', function() {
                $('#margin').val('')
                $('.modal-body form')[0].reset();
                $('.btn-loading').addClass('d-none')
                $('#btn-share-wa').removeClass('d-none')
            })

            $('#bs-datepicker-daterange').datepicker({
                todayHighlight: true,
                format: 'mm/dd/yyyy',
                autoclose: true,
            })
        })

        $(document).on('click', '#copy-sn-detail', function() {
            var snText = $(this).data('sn');
            navigator.clipboard.writeText(snText).then(function() {
                alert('Berhasil Dicopy: ' + snText)
            }, function(err) {
                alert('Gagal Dicopy: ' + err.responseJSON.message)
            });
        });
    </script>
@endpush
