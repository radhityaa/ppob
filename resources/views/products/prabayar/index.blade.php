@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
                <div class="d-md-flex gap-2">
                    <button type="button" class="btn btn-success mb-3" id="getProvider">
                        Update
                    </button>
                    <button type="button" class="btn btn-danger mb-3" id="deleteAll">
                        Delete All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="dataTable table border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalPaymentMethod" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPaymentMethodTitle">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="" id="form">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="group" class="form-label">Group</label>
                                <input type="text" id="group" name="group" class="form-control" value="manual"
                                    placeholder="Ex: Virtual Account" disabled />
                            </div>
                            <div class="col mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" id="code" name="code" class="form-control"
                                    placeholder="Ex: MYBVA" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Ex: Maybank Virtual Account" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="fee" class="form-label">Fee</label>
                                <input type="number" id="fee" name="fee" class="form-control" placeholder="Ex: 0"
                                    required />
                            </div>
                            <div class="col mb-3">
                                <label for="percent_fee" class="form-label">Percent Fee</label>
                                <input type="number" id="percent_fee" name="percent_fee" class="form-control"
                                    placeholder="Ex: 0.0" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="provider" class="form-label">Provider</label>
                                <input type="text" id="provider" name="provider" class="form-control" value="manual"
                                    disabled />
                            </div>
                            <div class="col mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non-Aktif</option>
                                </select>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                    <x-button-loading />
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <script>
        let url = ''
        let method = ''

        function getUrl() {
            return url
        }

        function getMethod() {
            return method
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('prabayar.index') }}",
            columnDefs: [{
                "targets": "_all",
                "className": "text-start"
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'buyer_sku_code',
                    name: 'buyer_sku_code'
                },
                {
                    data: 'product_name',
                    name: 'product_name'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'brand',
                    name: 'brand'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'buyer_product_status',
                    name: 'buyer_product_status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function refresh() {
            table.ajax.reload(null, false)
        }

        $('#createPaymentMethod').on('click', function() {
            $('#modalPaymentMethod').modal('show')
            $('#modalPaymentMethodTitle').text('Tambah Payment Method Manual')
            $('#provider').val('manual')
            $('#provider').prop('disabled', true)
            $('#provider').prop('required', false)
            url = "{!! route('payment-method.store') !!}"
            method = "POST"
        })

        $('#getProvider').on('click', function() {
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

            $.ajax({
                url: "{{ route('prabayar.getServices') }}",
                method: "GET",
                dataType: "json",
                success: function(res) {
                    refresh()
                    Swal.close()
                    Swal.fire({
                        title: 'Success!',
                        text: 'Data fetched successfully.',
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
                    refresh()
                    Swal.close()
                    Swal.fire({
                        title: 'Error!',
                        text: err.responseJSON.message,
                        icon: 'error'
                    })
                }
            })
        })

        $('#deleteAll').on('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "Deleted data cannot be restored!",
                icon: 'warning',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#82868',
            }).then((result) => {
                if (result.isConfirmed) {
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

                    $.ajax({
                        url: "{{ route('prabayar.deleteServices') }}",
                        method: "DELETE",
                        dataType: "json",
                        success: function(res) {
                            refresh()
                            Swal.close()
                            Swal.fire({
                                title: 'Success!',
                                text: 'Data deleted successfully.',
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
                            refresh()
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
                }
            });
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')

            $('#modalPaymentMethod').on('hidden.bs.modal', function() {
                $('.btn-loading').addClass('d-none')
                $('.btn-save').removeClass('d-none')
                $('.modal-body form')[0].reset();
                $('input[name="_method"]').remove();
            })

            $('#form').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')

                $.ajax({
                    url: url,
                    method: method,
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(res) {
                        $('#modalPaymentMethod').modal('hide')
                        refresh()

                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        })
                    },
                    error: function(err) {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')
                        Swal.fire({
                            title: 'Error!',
                            text: err.responseJSON.message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        });
                    },
                    complete: function() {
                        $('.btn-loading').addClass('d-none')
                        $('.btn-save').removeClass('d-none')
                    }
                })
            })
        })
    </script>
@endpush
