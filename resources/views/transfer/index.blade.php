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
                <button type="button" class="btn btn-primary mb-3" id="createTransfer">
                    <i class="ti ti-plus"></i>
                    Transfer
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
            <table class="dataTable table border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Tujuan</th>
                        <th>Total</th>
                        <th>Ket</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade" id="modalTransfer" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTransferTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <select name="username" id="username" class="select2 form-select" required>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="amount" class="form-label">Nominal</label>
                                    <input type="text" id="amount" name="amount" class="form-control" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="description" id="description" class="form-label">Keterangan
                                        (Opsional)</label>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary btn-transfer">Transfer</button>
                        <x-button-loading />
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-3">
        <!-- Modal Detail-->
        <div class="modal fade modal-sm" id="modalDetailTransfer" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailTransferTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table">
                            <thead>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <th class="fw-semibold">Tanggal</th>
                                    <td class="fw-bold" id="date"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Tujuan</th>
                                    <td id="username-detail"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Total</th>
                                    <td id="amount-detail"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Ket</th>
                                    <td id="description-detail"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
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
            ajax: "{{ route('transfer.index') }}",
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
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'description',
                    name: 'description'
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

        function numberFormatIdr(value) {
            var roundedValue = Math.round(value);
            var reverse = roundedValue.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + formatted;
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

        $('#createTransfer').on('click', function() {
            listUsers()
            $('#modalTransfer').modal('show')
            $('#modalTransferTitle').text('Transfer Saldo')
            url = "{!! route('payment-method.store') !!}"
            method = "POST"
        })

        $('body').on('click', '#detailTf', function() {
            var invoice = $(this).data('invoice')
            $('#modalDetailTransfer').modal('show')

            let url = "{{ route('transfer.show', ':invoice') }}"
            url = url.replace(':invoice', invoice)

            $('#modalDetailTransferTitle').html('Loading...')
            $('#username-detail').html('Loading...')
            $('#amount-detail').html('Loading...')
            $('#description-detail').html('Loading...')
            $('#date').html('Loading...')

            $.ajax({
                url: url,
                method: 'GET',
                success: function(res) {
                    $('#modalDetailTransferTitle').html(res.invoice)
                    $('#username-detail').html(res.username)
                    $('#amount-detail').html(res.amount)
                    $('#description-detail').html(res.description)
                    $('#date').html(res.created_at)
                },
                error: function(err) {
                    $('#modalDetailTransferTitle').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#username-detail').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#amount-detail').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#description-detail').html('Terjadi Kesalahan, Ulangi Lagi')
                    $('#date').html('Terjadi Kesalahan, Ulangi Lagi')
                }
            })
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-transfer').removeClass('d-none')

            $('#modalTransfer').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-transfer').removeClass('d-none')
                $('.modal-body form')[0].reset();
            })

            $('#username').select2({
                dropdownParent: $('#modalTransfer')
            })

            $('#amount').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#amount').val(formatted);
            })

            $('#form').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-transfer').addClass('d-none')
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
                        $('.btn-transfer').removeClass('d-none')
                        $('#modalTransfer').modal('hide')
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
                        $('.btn-transfer').removeClass('d-none')
                        $('#modalTransfer').modal('hide')
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
