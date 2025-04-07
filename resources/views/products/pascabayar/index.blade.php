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
                        <button type="button" class="btn btn-success mb-3" id="getProduct">
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
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 py-2">
                    <label for="service" class="form-label">Layanan</label>
                    <select class="form-select form-control" id="service">
                        <option value="" selected disabled>-- Pilih Layanan --</option>
                        @foreach (getPascaBrand() as $item)
                            <option value="{{ $item->brand }}">{{ $item->brand }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 py-2" style="display: none;" id="productEl">
                    <label for="product" class="form-label">Product</label>
                    <select class="form-select form-control" id="product">
                        <option value="" selected disabled>-- Pilih Product --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="row g-4" id="servicesCon">
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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

        $('#getProduct').on('click', function() {
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
                url: "{{ route('pascabayar.getServicesDigiflazz') }}",
                method: "GET",
                dataType: "json",
                success: function(res) {
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

                    setTimeout(() => {
                        window.location.reload();
                    }, 1600)
                },
                error: function(err) {
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
                        url: "{{ route('pascabayar.deleteServices') }}",
                        method: "DELETE",
                        dataType: "json",
                        success: function(res) {
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

                            setTimeout(() => {
                                window.location.reload();
                            }, 1600)
                        },
                        error: function(err) {
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

        $('select#service').on('change', function() {
            var service = $(this).val()
            var servicesContainer = $('#servicesCon')

            $.ajax({
                url: "{{ route('pascabayar.getProduct') }}",
                method: "GET",
                data: {
                    service: service,
                },
                success: function(res) {
                    servicesContainer.empty(); // Clear the existing content

                    // Assuming 'res' is an array of objects
                    $.each(res.data, function(i, service) {
                        if (res.role === 'admin') {
                            service.admin = service.admin
                        } else if (res.role === 'member') {
                            service.admin = service.admin_member
                        } else if (res.role === 'reseller') {
                            service.admin = service.admin_reseller
                        } else if (res.role === 'agen') {
                            service.admin = service.admin_agen
                        }

                        var formatPrice = new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 0,
                            currency: 'IDR'
                        }).format(service.admin)

                        if (res.role === 'admin') {
                            var formatCommision = new Intl.NumberFormat('id-ID', {
                                minimumFractionDigits: 0,
                                currency: 'IDR'
                            }).format(service.commission)
                        }

                        var badgeClass = service.seller_product_status ? 'bg-label-primary' :
                            'bg-label-danger';
                        var textClass = service.seller_product_status ? 'text-primary' :
                            'text-danger';
                        var buttonDisabled = !service.seller_product_status ? 'disabled' :
                            '';
                        var bgColor = service.seller_product_status ? 'bg-primary' :
                            'bg-danger';

                        var cardHtml = `
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="badge ${badgeClass}">${service.seller_product_status ? 'Normal' : 'Gangguan'}</span>
                                    <div class="d-flex justify-content-center">
                                        <sup class="h6 fw-medium pricing-currency mt-3 mb-0 me-1 ${textClass}">Rp</sup>
                                        <h1 class="mb-0 ${textClass}">${formatPrice}</h1>
                                    </div>
                                </div>
                                <ul class="ps-3 g-2">
                                    <li class="text-truncate-multiline">${service.product_name}</li>
                                    ${res.role === 'admin' ? '<li class="text-truncate-multiline">Komisi: ' + formatCommision + '</li>' : ''}
                                    <li class="text-truncate-multiline">${service.description}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
                        servicesContainer.append(cardHtml);
                    });
                },
                error: function(err) {
                    console.log(err);
                }
            })
        })

        $(document).ready(function() {
            $('.btn-loading').addClass('d-none')
            $('.btn-save').removeClass('d-none')

            $('#form').on('submit', function(e) {
                e.preventDefault()
                $('.btn-loading').removeClass('d-none')
                $('.btn-save').addClass('d-none')
            })
        })
    </script>
@endpush
