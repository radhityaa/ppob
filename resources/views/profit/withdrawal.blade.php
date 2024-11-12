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
    <div class="col-md-12">
        <div class="d-md-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            <button class="btn btn-primary" onclick="withdrawal()">Tarik</button>
        </div>
    </div>

    <!-- List Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="dataTable border-top table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Invoice</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade" id="modalWithdrawal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalWithdrawalTitle">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="" id="form-tarik">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="total" class="form-label">Nominal</label>
                                    <input type="text" id="total" name="total" class="form-control" required />
                                    <div id="defaultFormControlHelp" class="form-text">
                                        Minimal Penarikan
                                        <strong>{{ number_format($settingProfit['minimal_withdrawal'], 0, '.', '.') }}</strong>.
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary btn-wd">Tarik</button>
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
            ajax: "{{ route('profits.withdrawal.index') }}",
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
                    data: 'invoice',
                    name: 'invoice'
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ],
            responsive: true,
        })

        function refresh() {
            table.ajax.reload(null, false)
        }

        function withdrawal() {
            $('#modalWithdrawal').modal('show')
            $('#modalWithdrawalTitle').text('Tarik Profit')
        }

        function numberFormatIdr(value) {
            var roundedValue = Math.round(value);
            var reverse = roundedValue.toString().split('').reverse().join('');
            var ribuan = reverse.match(/\d{1,3}/g);
            var formatted = ribuan.join('.').split('').reverse().join('');
            return 'Rp ' + formatted;
        }

        $(document).ready(function() {
            let btnWd = $('.btn-wd')
            let btnLoading = $('.btn-loading')

            $('#modalWithdrawal').on('show.bs.modal', function() {
                btnWd.removeClass('d-none')
                btnLoading.addClass('d-none')
                $('.modal-body form')[0].reset();
            })

            $('#total').on('input', function() {
                // Ambil nilai input
                var inputValue = $(this).val();

                // Hilangkan semua karakter selain angka
                var numericValue = inputValue.replace(/Rp|\./g, '');

                // Konversi ke integer
                var integerValue = parseInt(numericValue, 10);

                // Format kembali sebagai Rupiah
                var formatted = numberFormatIdr(integerValue);

                $(this).val(formatted);
                $('#total').val(formatted);
            })

            $('#form-tarik').on('submit', function(e) {
                e.preventDefault()
                btnWd.addClass('d-none')
                btnLoading.removeClass('d-none')

                var formData = new FormData()
                var total = parseInt($('#total').val().replace(/Rp|\./g, ''))
                formData.append('total', total)

                $.ajax({
                    url: "{{ route('profits.withdrawal.store') }}",
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            btnWd.removeClass('d-none')
                            btnLoading.addClass('d-none')
                            $('#modalWithdrawal').modal('hide')
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                            refresh()
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                        btnWd.removeClass('d-none')
                        btnLoading.addClass('d-none')
                    },
                    error: function(err) {
                        btnWd.removeClass('d-none')
                        btnLoading.addClass('d-none')
                        $('#modalWithdrawal').modal('hide')
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.responseJSON.message
                        })
                        refresh()
                    },
                    complete: function() {
                        btnWd.removeClass('d-none')
                        btnLoading.addClass('d-none')
                    }
                })
            })
        })
    </script>
@endpush
