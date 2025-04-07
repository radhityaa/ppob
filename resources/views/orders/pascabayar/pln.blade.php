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
                <input type="hidden" id="buyer_sku_code" value="{{ $data->buyer_sku_code }}">
                <label for="target" class="form-label">No Pelanggan</label>
                <input type="text" name="target" id="target" value="{{ old('target') }}" placeholder="No. Pelanggan"
                    class="form-control" autofocus required />
            </div>
            <div>
                <button class="btn btn-success btn-bill">
                    Cek Tagihan
                </button>
                <x-button-loading />
                <button class="btn btn-info btn-pay-bill">
                    Bayar Tagihan
                </button>
            </div>
        </div>
    </div>

    <div class="container-customer container pt-4">
    </div>

    <div class="container">
        <div class="row">
            <div class="accordion mt-3" id="container-bill">
            </div>
        </div>
    </div>

    {{-- Detail Product --}}
    <x-detail-product />
    {{-- / Detail Product --}}
@endsection

@push('page-js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                currency: 'IDR'
            }).format(price);
        }

        function formatPeriod(periode) {
            const year = periode.substring(0, 4)
            const monthIndex = parseInt(periode.substring(4, 6)) - 1

            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ]

            return `${monthNames[monthIndex]} ${year}`
        }

        $(document).ready(function() {
            $('#target').val('')
            $('.btn-loading').addClass('d-none')
            $('.btn-bill').removeClass('d-none')
            $('.btn-buy').removeClass('d-none')
            $('.saldo').hide();
            $('#brand').val('');
        })

        $('.btn-bill').on('click', function() {
            var target = $('#target').val();
            var buyerSkuCode = $('#buyer_sku_code').val();

            $('.btn-loading').removeClass('d-none')
            $('.btn-bill').addClass('d-none')

            if (target == '') {
                Swal.fire({
                    title: 'Peringatan!',
                    text: "No. Pelanggan Tidak Boleh Kosong!",
                    icon: 'warning',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#82868',
                })

                $('.btn-loading').addClass('d-none')
                $('.btn-bill').removeClass('d-none')

                return false;
            }

            $.ajax({
                url: "{{ route('trx.pascabayar') }}",
                method: "POST",
                data: {
                    target,
                    buyerSkuCode,
                    type: "bill"
                },
                success: function(res) {
                    console.log(res);
                    $('.btn-loading').addClass('d-none')
                    $('.btn-bill').removeClass('d-none')

                    let totalBill = 0
                    let totalFine = 0
                    $.each(res.data?.desc?.detail, function(index, item) {
                        totalBill += parseFloat(item.nilai_tagihan)
                        totalFine += parseFloat(item.denda)
                    })

                    var containerCustomer = $('.container-customer')
                    containerCustomer.empty()

                    var html = `
                        <div class="row g-2">
                            <div class="col">
                                <div class="card">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <tbody class="table-border-bottom-0">
                                                <tr>
                                                    <td>Nama Pelanggan</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">${res.data.customer_name}</td>
                                                </tr>
                                                <tr>
                                                    <td>No. Pelanggan</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">${res.data.customer_no}</td>
                                                </tr>
                                                <tr>
                                                    <td>Daya</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">${res.data.desc.daya}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tarif</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">${res.data.desc.tarif}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table">
                                            <tbody class="table-border-bottom-0">
                                                <tr>
                                                    <td>Total Tagihan</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">Rp ${formatPrice(totalBill)}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Admin</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">Rp ${formatPrice(res.data.admin)}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Denda</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">${totalFine}</td>
                                                </tr>
                                                <tr>
                                                    <td>Subtotal</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="text-danger fw-bold">Rp ${formatPrice(res.data.selling_price)}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `

                    containerCustomer.append(html)

                    var containerBill = $('#container-bill')
                    containerBill.empty()

                    $.each(res.data?.desc?.detail, function(index, item) {
                        var html = `
                            <div class="card accordion-item">
                                <h2 class="accordion-header" id="heading${index+1}">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordion${index+1}" aria-expanded="false" aria-controls="accordion${index+1}">
                                        Lembar Tagihan Ke ${index + 1}
                                    </button>
                                </h2>

                                <div id="accordion${index+1}" class="accordion-collapse collapse" data-bs-parent="#accordionExample"
                                    style="">
                                    <div class="accordion-body">
                                        <table class="table">
                                            <tbody class="table-border-bottom-0">
                                                <tr>
                                                    <td>Periode</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">${formatPeriod(item.periode)}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tagihan</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">Rp ${formatPrice(item.nilai_tagihan)}</td>
                                                </tr>
                                                <tr>
                                                    <td>Denda</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">Rp ${formatPrice(item.denda)}</td>
                                                </tr>
                                                <tr>
                                                    <td>Admin</td>
                                                    <td style="padding: 0px;">:</td>
                                                    <td style="padding-left: 5px;" class="fw-semibold">Rp ${formatPrice(item.admin)}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        `
                        containerBill.append(html)
                    })
                },
                error: function(err) {
                    console.log(err);
                    $('.btn-loading').addClass('d-none')
                    $('.btn-bill').removeClass('d-none')
                },
                complete: function() {
                    $('.btn-loading').addClass('d-none')
                    $('.btn-bill').removeClass('d-none')
                }
            })
        })

        $('.btn-pay-bill').on('click', function() {
            var target = $('#target').val();
            var buyerSkuCode = $('#buyer_sku_code').val();

            if (target == '') {
                Swal.fire({
                    title: 'Peringatan!',
                    text: "No. Pelanggan Tidak Boleh Kosong!",
                    icon: 'warning',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#82868',
                })

                $('.btn-loading').addClass('d-none')
                $('.btn-pay-bill').removeClass('d-none')

                return false;
            }

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Pastikan Target/Nomor Pelanggan Sudah Benar!",
                icon: 'warning',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#82868',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.btn-loading').removeClass('d-none')
                    $('.btn-pay-bill').addClass('d-none')

                    $.ajax({
                        url: "{{ route('trx.pascabayar') }}",
                        method: "POST",
                        data: {
                            target,
                            buyerSkuCode,
                            type: "pay"
                        },
                        success: function(res) {
                            Swal.fire({
                                title: res.data.status,
                                text: res.data.message,
                                icon: 'success',
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#82868',
                            })

                            setInterval(() => {
                                window.location.href =
                                    '{{ route('history.prabayar') }}';
                            }, 2000);

                            $('.btn-loading').addClass('d-none')
                            $('.btn-pay-bill').removeClass('d-none')
                        },
                        error: function(err) {
                            console.log(err)
                            $('.btn-loading').addClass('d-none')
                            $('.btn-pay-bill').removeClass('d-none')
                        },
                        complete: function() {
                            $('.btn-loading').addClass('d-none')
                            $('.btn-pay-bill').removeClass('d-none')
                        }
                    })
                }
            })
        })
    </script>
@endpush
