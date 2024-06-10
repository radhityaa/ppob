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
            <div class="col mb-1">
                <label for="target" class="form-label">Tujuan</label>
                <input type="number" name="target" id="target" value="{{ old('target') }}" placeholder="Ex: 0895xxxxx"
                    class="form-control" required />
            </div>
            <div class="col mt-1">
                <span id="provider" class="badge" style="display: none;"></span>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="row g-4" id="services">
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $(document).ready(function() {
            $('#target').val('')

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        })

        $('#target').on('keyup', function() {
            var target = $('#target').val();

            $.ajax({
                url: "{{ route('prabayar.pulsa') }}",
                method: "GET",
                data: {
                    target: target
                },
                success: function(res) {
                    if (res.status) {
                        $('#provider').show()
                        $('#provider').removeClass('bg-danger');
                        $('#provider').addClass('bg-success');
                        $('#provider').text(res.message);

                        var servicesContainer = $('#services');
                        servicesContainer.empty(); // Clear the existing content

                        // Assuming 'res' is an array of objects
                        $.each(res.data, function(i, service) {
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
                                    <li>${service.type}</li>
                                    <li>${service.category}</li>
                                    <li class="text-truncate-multiline">${service.product_name}</li>
                                </ul>
                                <div class="d-grid w-100">
                                    <button class="btn ${textClass} ${bgColor} btn-sm" ${buttonDisabled} data-bs-target="#upgradePlanModal" data-bs-toggle="modal">
                                        <span class="text-white">${service.buyer_product_status ? 'Beli' : 'Gangguan'}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                            servicesContainer.append(cardHtml);
                        });
                    } else {
                        var services = $('#services');
                        services.empty();
                        $('#provider').show();
                        $('#provider').removeClass('bg-success');
                        $('#provider').addClass('bg-danger');
                        $('#provider').text(res.message);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
    </script>
@endpush
