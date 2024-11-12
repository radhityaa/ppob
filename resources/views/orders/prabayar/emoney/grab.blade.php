@extends('layouts.administrator.app')

@push('page-css')
    <style>
        .text-truncate-multiline {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-bold">{{ $title ?? '' }}</h4>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="col mb-3">
                <input type="hidden" id="brand" name="brand">
                <label for="target" class="form-label">Tujuan</label>
                <input type="number" name="target" id="target" value="{{ old('target') }}" placeholder="Ex: 0895xxxxx"
                    class="form-control" required />
            </div>
            <div class="col mb-3" id="typeEl" style="display: none;">
                <label for="typeSel" class="form-label">Kategori</label>
                <select name="typeSel" id="typeSel" class="form-control" required></select>
            </div>
            <div class="col">
                <span id="provider" class="badge" style="display: none;"></span>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="row g-4" id="services">
        </div>
    </div>

    {{-- Detail Product --}}
    <x-detail-product />
    {{-- / Detail Product --}}
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            $('#target').val('')
            $('#buyer_sku_code').html('');
            $('.btn-loading').addClass('d-none')
            $('.btn-buy').removeClass('d-none')
            $('.saldo').hide();
            $('#brand').val('');
            $('#typeSel').empty()

            $('#offcanvasBottom').on('hidden.bs.offcanvas', function() {
                $('#target-detail').html('');
                $('.offcanvas-title').html('');
                $('#type').html('');
                $('#price').html('');
                $('#description').html('');
                $('#multi').html('');
                $('#cut-off').html('');
                $('#buyer_sku_code').html('');
                $('.saldo').hide();
                $('.btn-buy').attr('disabled', false);

                $('.btn-loading').addClass('d-none')
                $('.btn-buy').removeClass('d-none')
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        })

        function detailProduct(id) {
            url = "{{ route('prabayar.detailServices', ':id') }}"
            url = url.replace(':id', id);

            var target = $('#target').val();
            $('#target-detail').html('loading...');
            $('.offcanvas-title').html('loading...');
            $('#type').html('loading...');
            $('#price').html('loading...');
            $('#description').html('loading...');
            $('#multi').html('loading...');
            $('#cut-off').html('loading...');
            $('#saldo').html('loading...');

            $.ajax({
                url: url,
                method: "GET",
                success: function(res) {
                    if (res.saldo < res.data.price) {
                        $('.btn-buy').attr('disabled', 'disabled');
                        $('.saldo').show();
                    }

                    if (res.data.multi) {
                        $('#multi').html('YA');
                    } else {
                        $('#multi').html('TIDAK');
                    }

                    var formatPrice = new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        currency: 'IDR'
                    }).format(res.data.price)

                    var formatSaldo = new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                        currency: 'IDR'
                    }).format(res.saldo)

                    $('#target-detail').html(target);
                    $('.offcanvas-title').html(res.data.product_name);
                    $('#type').html(res.data.category + ' • ' + res.data.type);
                    $('#price').html('Rp ' + formatPrice);
                    $('#saldo').html('Rp ' + formatSaldo);
                    $('#description').html(res.data.desc);
                    $('#cut-off').html(res.data.start_cut_off + ' s/d ' + res.data.end_cut_off);
                    $('#buyer_sku_code').html(res.data.buyer_sku_code);
                },
                error: function(err) {
                    $('#target-detail').html('Terjadi Kesalahan, Ulangi Lagi');
                    $('#type').html('Terjadi Kesalahan, Ulangi Lagi');
                    $('#price').html('Terjadi Kesalahan, Ulangi Lagi');
                    $('#description').html('Terjadi Kesalahan, Ulangi Lagi');
                    $('#multi').html('Terjadi Kesalahan, Ulangi Lagi');
                    $('#cut-off').html('Terjadi Kesalahan, Ulangi Lagi');
                    $('#offcanvas-title').html('Terjadi Kesalahan, Ulangi Lagi');
                    $('#buyer_sku_code').html('');
                }
            })
        }

        $('#buy').on('click', function() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Pastikan Target/Nomor Tujuan Sudah Benar!",
                icon: 'warning',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#82868',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.btn-loading').removeClass('d-none')
                    $('.btn-buy').addClass('d-none')

                    var target = $('#target').val();
                    var buyerSkuCode = $('#buyer_sku_code').html();

                    $.ajax({
                        url: "{{ route('transaction.store') }}",
                        method: "POST",
                        data: {
                            target,
                            buyerSkuCode
                        },
                        success: function(res) {
                            $('.btn-loading').addClass('d-none')
                            $('.btn-buy').removeClass('d-none')

                            $('#offcanvasBottom').offcanvas('hide')

                            Swal.fire({
                                title: 'Success!',
                                text: res.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'd-none'
                                },
                                buttonsStyling: false,
                            })

                            setInterval(() => {
                                window.location.href =
                                    '{{ route('history.prabayar') }}';
                            }, 2000);
                        },
                        error: function(err) {
                            $('.btn-loading').addClass('d-none')
                            $('.btn-buy').removeClass('d-none')

                            $('#offcanvasBottom').offcanvas('hide')

                            Swal.fire({
                                title: 'Gagal!',
                                text: err.responseJSON.message,
                                icon: 'error',
                            })
                        }
                    })
                }
            });
        })

        $('#target').on('keyup', function() {
            var target = $('#target').val();
            let typeSelect = $('#typeSel')
            let opt = ''
            var typeEl = $('#typeEl')
            typeSelect.empty()

            $.ajax({
                url: "{{ route('category.show') }}",
                method: "GET",
                data: {
                    category: 'E-Money',
                    brand: 'GRAB'
                },
                success: function(res) {
                    if (res.status) {
                        typeEl.show()
                        typeSelect.empty()
                        $('#provider').show()
                        $('#provider').removeClass('bg-danger');
                        $('#provider').addClass('bg-success');
                        $('#provider').text(res.message);
                        $('#brand').val(res.message);

                        opt = '<option value="" disabled selected>Pilih Kategori</option>'
                        $.each(res.data, function(key, val) {
                            opt += '<option value="' + val + '">' + val + '</option>'
                        })
                        typeSelect.append(opt);
                    } else {
                        var services = $('#services');
                        typeEl.hide()
                        typeSelect.empty()
                        services.empty();
                        $('#brand').val('');
                        $('#provider').show();
                        $('#provider').removeClass('bg-success');
                        $('#provider').addClass('bg-danger');
                        $('#provider').text(res.message);
                    }
                },
                error: function(err) {
                    typeEl.hide()
                    typeSelect.empty()
                    $('#brand').val('');
                }
            });
        });

        $('#typeSel').on('change', function() {
            var type = $('#typeSel').val()
            var brand = $('#brand').val()
            var servicesContainer = $('#services')

            $.ajax({
                url: "{{ route('prabayar.emoney.grab') }}",
                method: "GET",
                data: {
                    type,
                    brand
                },
                success: function(res) {
                    servicesContainer.empty(); // Clear the existing content

                    // Assuming 'res' is an array of objects
                    $.each(res, function(i, service) {
                        var formatPrice = new Intl.NumberFormat('id-ID', {
                            minimumFractionDigits: 0,
                            currency: 'IDR'
                        }).format(service.price)

                        var badgeClass = service.buyer_product_status ? 'bg-label-primary' :
                            'bg-label-danger';
                        var textClass = service.buyer_product_status ? 'text-primary' :
                            'text-danger';
                        var buttonDisabled = !service.buyer_product_status ? 'disabled' :
                            '';
                        var bgColor = service.buyer_product_status ? 'bg-primary' :
                            'bg-danger';

                        var cardHtml = `
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="badge ${badgeClass}">${service.buyer_product_status ? 'Normal' : 'Gangguan'}</span>
                                    <div class="d-flex justify-content-center">
                                        <sup class="h6 fw-medium pricing-currency mt-3 mb-0 me-1 ${textClass}">Rp</sup>
                                        <h1 class="mb-0 ${textClass}">${formatPrice}</h1>
                                    </div>
                                </div>
                                <ul class="ps-3 g-2">
                                    <li class="text-truncate-multiline">${service.category} • ${service.type}</li>
                                    <li class="text-truncate-multiline">${service.product_name}</li>
                                    <li class="text-truncate-multiline mt-3">${service.desc}</li>
                                </ul>
                                <div class="d-grid w-100">
                                    <button class="btn ${textClass} ${bgColor} btn-sm" ${buttonDisabled} onclick="detailProduct(${service.id})" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">
                                        <span class="text-white">${service.buyer_product_status ? 'Beli' : 'Gangguan'}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                        servicesContainer.append(cardHtml);
                    });
                },
                error: function(err) {
                    console.log(err)
                }
            })
        })
    </script>
@endpush
