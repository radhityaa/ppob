@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />

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
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
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
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
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
                        <div class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
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
    <!-- List Table -->

    <div class="card">
        <div class="d-flex justify-content-end py-2">
            <button class="btn btn-primary btn-sm" onclick="refresh()">Refresh</button>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dataTable table border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Target</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>SN</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade modal-sm" id="modalDetail" tabindex="-1" aria-hidden="true">
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
                                    <td id="target"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Produk</th>
                                    <td id="product_name"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">SN</th>
                                    <td id="sn"><span id="sn-text"></span> <span class="copy-text-detail"
                                            data-sn="" id="copy-sn-detail">(copy)</span></td>
                                </tr>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Ket</th>
                                    <td id="message"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Harga</th>
                                    <td id="price"></td>
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

    <div class="mt-3">
        {{-- Modal Share --}}
        <div class="modal fade modal-sm" id="modalShare" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row gap-2">
                            <div class="col-12">
                                <button class="w-100 btn btn-success"><i class="ti ti-brand-whatsapp me-1"></i>
                                    Whatsapp</button>
                            </div>
                            <div class="col-12">
                                <button id="print" class="w-100 btn btn-info"><i class="ti ti-printer me-1"></i>
                                    Print</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{-- Modal Margin --}}
        <div class="modal fade modal-sm" id="modalMargin" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{ route('history.prabayar.print') }}" method="POST">
                            @csrf
                            <input type="hidden" name="invoice" id="invoice">
                            <div class="mb-3">
                                <label for="margin" class="form-label">Harga Jual</label>
                                <input type="text" name="margin" id="margin" class="form-control" required>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" id="print" class="w-100 btn btn-info"><i
                                        class="ti ti-printer me-1"></i>
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

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('history.prabayar') }}",
            columnDefs: [{
                // For Responsive
                className: 'control',
                orderable: false,
                searchable: false,
                responsivePriority: 3,
                targets: 0,
                render: function(data, type, full, meta) {
                    return '';
                }
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'invoice',
                    name: 'invoice'
                },
                {
                    data: 'target',
                    name: 'target'
                },
                {
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'sn',
                    name: 'sn'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            responsive: true,
        });

        function refresh() {
            table.ajax.reload(null, false)
        }

        $('body').on('click', '#detail', function() {
            $('#modalDetail').modal('show')
            var invoice = $(this).data('invoice')

            $('#modalDetailTitle').html('Loading...')
            $('#date').html('Loading...')
            $('#target').html('Loading...')
            $('#product_name').html('Loading...')
            $('#sn-text').html('Loading...');
            $('#copy-sn-detail').data('sn', ''); // Clear previous SN data attribute
            $('#message').html('Loading...')
            $('#price').html('Loading...')
            $('#status').html('Loading...')

            let url = "{{ route('history.prabayar.detail', ':invoice') }}"
            url = url.replace(':invoice', invoice)

            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    $('#modalDetailTitle').html(res.invoice)
                    $('#date').html(res.created_at)
                    $('#target').html(res.target)
                    $('#product_name').html(res.product_name)
                    $('#sn-text').html(res.sn);
                    $('#copy-sn-detail').data('sn', res.sn); // Set SN data attribute for copy
                    $('#message').html(res.message)
                    $('#price').html(res.price)
                    $('#status').html(res.status)
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

        $('body').on('click', '#share', function() {
            $('#modalShare').modal('show')
            var invoice = $(this).data('invoice')

            $('#print').on('click', function() {
                $('#invoice').val(invoice)
                $('#modalShare').modal('hide')
                $('#modalMargin').modal('show')
            })
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

            $('#modalMargin').on('hidden.bs.modal', function() {
                $('#margin').val('')
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
