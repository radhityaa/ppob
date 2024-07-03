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
                @role('admin')
                    <div class="d-md-flex gap-2">
                        <button type="button" class="btn btn-success mb-3" id="getProvider">
                            Update
                        </button>
                        <button type="button" class="btn btn-danger mb-3" id="deleteAll">
                            Delete All
                        </button>
                    </div>
                @endrole
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
                        @role('admin')
                            <th></th>
                        @endrole
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <!-- Modal -->
        <div class="modal fade modal-lg" id="modalDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetailTitle">Detail Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table mt-2">
                            <thead>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                <tr>
                                    <th class="fw-semibold">Product</th>
                                    <td class="fw-bold" id="product_name"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Category</th>
                                    <td id="category"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Brand</th>
                                    <td id="brand"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Type</th>
                                    <td id="type"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Seller Name</th>
                                    <td id="seller_name"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Price</th>
                                    <td id="price"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">SKU</th>
                                    <td id="buyer_sku_code"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Buyer Status</th>
                                    <td id="buyer_product_status"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Seller Status</th>
                                    <td id="seller_product_status"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Unli Stock</th>
                                    <td id="unlimited_stock"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Stock</th>
                                    <td id="stock"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Multi</th>
                                    <td id="multi"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Cut Off</th>
                                    <td id="cut_off"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Description</th>
                                    <td id="desc"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Provider</th>
                                    <td id="provider"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Created At</th>
                                    <td id="created_at"></td>
                                </tr>
                                <tr>
                                    <th class="fw-semibold">Updated At</th>
                                    <td id="updated_at"></td>
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

        var adminRole = "{{ $role }}"

        var columns = [{
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
                data: 'seller_product_status',
                name: 'seller_product_status'
            }
        ];

        if (adminRole) {
            columns.push({
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            });
        }

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('prabayar.index') }}",
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
            columns: columns,
            responsive: true,
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

        $('body').on('click', '#detailProduct', function() {
            var code = $(this).data('code')
            $('#modalDetail').modal('show')

            let url = "{{ route('prabayar.show', ':buyer_sku_code') }}"
            url = url.replace(':buyer_sku_code', code)

            $('#product_name').html('Loading...')
            $('#category').html('Loading...')
            $('#brand').html('Loading...')
            $('#type').html('Loading...')
            $('#seller_name').html('Loading...')
            $('#price').html('Loading...')
            $('#buyer_sku_code').html('Loading...')
            $('#buyer_product_status').html('Loading...')
            $('#seller_product_status').html('Loading...')
            $('#unlimited_stock').html('Loading...')
            $('#stock').html('Loading...')
            $('#multi').html('Loading...')
            $('#cut_off').html('Loading...')
            $('#desc').html('Loading...')
            $('#created_at').html('Loading...')
            $('#updated_at').html('Loading...')
            $('#provider').html('Loading...')

            $.ajax({
                url: url,
                method: "GET",
                dataType: "json",
                success: function(res) {
                    $('#product_name').html(res.product_name)
                    $('#category').html(res.category)
                    $('#brand').html(res.brand)
                    $('#type').html(res.type)
                    $('#seller_name').html(res.seller_name)
                    $('#price').html(res.price)
                    $('#buyer_sku_code').html(res.buyer_sku_code)
                    $('#buyer_product_status').html(res.buyer_product_status)
                    $('#seller_product_status').html(res.seller_product_status)
                    $('#unlimited_stock').html(res.unlimited_stock)
                    $('#stock').html(res.stock)
                    $('#multi').html(res.multi)
                    $('#cut_off').html(res.cut_off)
                    $('#desc').html(res.desc)
                    $('#created_at').html(res.created_at)
                    $('#updated_at').html(res.updated_at)
                    $('#provider').html(res.provider)
                },
                error: function(err) {
                    $('#product_name').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#category').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#brand').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#type').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#seller_name').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#price').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#buyer_sku_code').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#buyer_product_status').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#seller_product_status').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#unlimited_stock').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#stock').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#multi').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#cut_off').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#desc').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#created_at').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#updated_at').html('Terjadi Kesalahan, Silahkan Ulangi')
                    $('#provider').html('Terjadi Kesalahan, Silahkan Ulangi')
                }
            })
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
