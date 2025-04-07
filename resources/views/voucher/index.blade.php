@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
                <button type="button" class="btn btn-primary mb-3" id="createVoucher">
                    <i class="ti ti-plus"></i>
                    Voucher
                </button>
            </div>
        </div>
    </div>

    <!-- List Table -->
    <div class="card">
        <div class="d-flex justify-content-end py-2">
            <button class="btn btn-primary btn-sm" onclick="refresh()">Refresh</button>
        </div>
        <div class="card-datatable table-responsive">
            <table class="dataTable border-top table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pemilik</th>
                        <th>Invoice</th>
                        <th>Nama Voucher</th>
                        <th>Code</th>
                        <th>Total</th>
                        <th>Target</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade" id="modalVoucher" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalVoucherTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="name" class="form-label">Nama Voucher</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Spesial Tahun Baru" autofocus required>
                                </div>
                                <div class="col mb-3">
                                    <label for="code" class="form-label">Kode Voucher</label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        placeholder="TAHUNBARU24" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="type_target" class="form-label">Target</label>
                                    <select name="type_target" id="type_target" class="form-control">
                                        <option value="" selected disabled>--Pilih Target--</option>
                                        <option value="Public">Public</option>
                                        <option value="Private">Private</option>
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <label for="type_voucher" class="form-label">Tipe Voucher</label>
                                    <select name="type_voucher" id="type_voucher" class="form-control">
                                        <option value="" selected disabled>--Pilih Tipe--</option>
                                        <option value="Saldo">Saldo</option>
                                        @role('admin')
                                            <option value="Discount Harga">Discount Harga</option>
                                            <option value="Discount Persen">Discount Persen (%)</option>
                                        @endrole
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="value" class="form-label">Nominal</label>
                                    <input type="text" id="value" name="value" class="form-control"
                                        placeholder="Dalam rupiah / %" required />
                                </div>
                                <div class="col mb-3">
                                    <label for="qty" class="form-label">Total Voucher</label>
                                    <input type="number" id="qty" name="qty" class="form-control" placeholder="5"
                                        required />
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary btn-voucher">Simpan</button>
                        <x-button-loading />
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('voucher.index') }}",
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
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'invoice',
                    name: 'invoice'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'qty',
                    name: 'qty'
                },
                {
                    data: 'type_target',
                    name: 'type_target'
                },
                {
                    data: 'type_voucher',
                    name: 'type_voucher'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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

        function listUsers() {
            let wrapper = $('#username')
            let option = ''
            wrapper.empty();
            $.get('{{ route('users.list') }}', function(res) {
                option += '<option value="">-- Pilih Username --</option>';

                $.each(res.data, function(i, val) {
                    option += '<option value="' + val.username + '">' + val.username + '</option>';
                });
                wrapper.append(option);
            })
        }

        $('#createVoucher').on('click', function() {
            listUsers()
            $('#modalVoucher').modal('show')
            $('#modalVoucherTitle').text('Buat Voucher')
            url = "{!! route('payment-method.store') !!}"
            method = "POST"
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-voucher').removeClass('d-none')

            $('#modalVoucher').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-voucher').removeClass('d-none')
                $('.modal-body form')[0].reset();
            })

            $('#username').select2({
                dropdownParent: $('#modalVoucher')
            })

            $('#form').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-voucher').addClass('d-none')
                let username = $('#username').val()
                var amount = parseInt($('#amount').val().replace(/Rp|\./g, ''))
                let description = $('#description').val()

                $.ajax({
                    url: "{{ route('transfer.store') }}",
                    method: "POST",
                    data: {
                        username,
                        amount,
                        description
                    },
                    dataType: "json",
                    success: function(res) {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-voucher').removeClass('d-none')
                        $('#modalVoucher').modal('hide')
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                        refresh()
                    },
                    error: function(res) {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-voucher').removeClass('d-none')
                        $('#modalVoucher').modal('hide')
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.responseJSON.message
                        })
                        refresh()
                    },
                })
            })
        })
    </script>
@endpush
