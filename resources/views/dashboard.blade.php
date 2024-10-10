@extends('layouts.administrator.app')

@push('page-css')
    <style>
        @media (min-width: 992px) {
            .w-lg-20 {
                width: 20% !important;
            }
        }

        .truncate-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Tentukan jumlah baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .truncate-3-lines {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Tentukan jumlah baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

@section('content')
    <!-- Profile -->
    <div class="col-xl-4 col-lg-5 col-12 mb-4">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Selamat Datang! 🎉</h5>
                        <p class="text-truncate mb-2">{{ Auth::user()->name }}</p>
                        <h4 class="text-primary mb-1">Rp {{ number_format(Auth::user()->saldo, 0, '.', '.') }}</h4>
                        <a href="{{ route('deposit.index') }}" class="btn btn-primary">Isi Saldo</a>
                    </div>
                </div>
                <div class="col-5 text-sm-left text-center">
                    <div class="card-body px-md-4 px-0 pb-0">
                        <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="140" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile -->

    <!-- Statistics -->
    <div class="col-xl-8 col-lg-7 col-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="card-title mb-0">Statistik</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3 col-12">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                <i class="ti ti-coins ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">Rp. {{ number_format($usedBalanceToday, 0, '.', '.') }}</h5>
                                <small>Saldo Terpakai Hari Ini</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-info me-3 p-2">
                                <i class="ti ti-coins ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">Rp. {{ number_format($usedBalanceMonth, 0, '.', '.') }}</h5>
                                <small>Saldo Terpakai Bulan Ini</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                <i class="ti ti-shopping-cart ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $transactionsToday }}</h5>
                                <small>Transaksi Hari Ini</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                                <i class="ti ti-shopping-cart ti-sm"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ $transactionsMonth }}</h5>
                                <small>Transaksi Bulan Ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Statistics -->

    {{-- Menu Service --}}
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-lg-8">
                @foreach ($listServices as $listService)
                    <div class="card mb-4 p-3">
                        <p class="h5 font-weight-semibold card-title mb-3 dark:text-white">{{ $listService->title }}</p>
                        <div class="w-100" style="height: 1px; background-color: #e0e0e0;"></div>
                        <div class="row">
                            @foreach ($listService->rechargeItems as $rechargeItem)
                                <div class="col-4 col-md-2 d-flex flex-column align-items-center">
                                    <a href="{{ route($rechargeItem->route) }}"
                                        class="d-block flex-grow-1 d-flex flex-column align-items-center justify-content-between py-4 text-center">
                                        <img src="{{ asset('assets/img/services/' . $rechargeItem->src) }}"
                                            class="img-fluid w-50 w-lg-20 mb-2">
                                        <p class="card-title mb-0 dark:text-white">{{ $rechargeItem->label }}</p>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <div class="d-flex justify-content-between">
                                <div><span style="font-size: 15px;">Informasi</span></div>
                                <div>
                                    <a href="{{ route('information.all') }}"
                                        style="font-size: 15px; text-decoration: underline">Lihat Semua</a>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="boardInformation" class="mt-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- /Menu Service --}}

    <!-- Modal Information -->
    <div class="mt-3">
        <div class="modal modal-lg fade modal-open" id="modalInformation" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalInformationTitle">Informasi Terbaru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="informationContainer">
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('information.all') }}" class="btn btn-primary">Lihat Semua</a>
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
    <script src="{{ asset('assets/js/purify.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        function updateStatusInformation() {
            $.ajax({
                url: '{{ route('information.update.user') }}',
                type: 'POST',
                data: {
                    userId: '{{ Auth::user()->id }}',
                },
                success: function(res) {
                    if (res.success) {
                        $('#modalInformation').modal('hide')
                    }
                }
            })
        }

        function getInformation() {
            $.get('{{ route('information.list') }}', function(res) {
                $.each(res, function(key, val) {
                    let urlView = "{!! route('information.show', ':slug') !!}"
                    urlView = urlView.replace(':slug', val.slug)

                    $('#informationContainer').append(`
                        <div>
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="${urlView}" class="fs-5">${val.title}</a>
                                <span class="badge text-uppercase ${val.type === 'Informasi' ? 'bg-info' : (val.type === 'Peringatan' ? 'bg-warning' : 'bg-danger')}">${val.type}</span>
                            </div>
                                <span style="font-size: 10px; m-0">By ${val.user.name}</span>

                            <div>
                                <span style="font-size: 11px;">${val.created_at}</span>
                            </div>
                                <span class="badge bg-secondary rounded-pill" style="font-size: 10px;">${val.category.name}</span>

                            <p class="truncate-3-lines">${DOMPurify.sanitize(val.description)}</p>
                        </div>
                        <hr>
                    `)

                    $('#boardInformation').append(`
                        <div>
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="${urlView}" class="fs-5">${val.title}</a>
                                <span class="badge text-uppercase ${val.type === 'Informasi' ? 'bg-info' : (val.type === 'Peringatan' ? 'bg-warning' : 'bg-danger')}">${val.type}</span>
                            </div>
                                <span style="font-size: 10px; m-0">By ${val.user.name}</span>

                            <div>
                                <span style="font-size: 11px;">${val.created_at}</span>
                            </div>
                                <span class="badge bg-secondary rounded-pill" style="font-size: 10px;">${val.category.name}</span>

                            <p class="truncate-2-lines">${DOMPurify.sanitize(val.description)}</p>
                        </div>
                        <hr>
                    `)
                })
            })
        }

        // function checkInformation() {
        //     if ({{ $showInformationModal }}) {
        //         $('#modalInformation').modal('show')
        //         getInformation();
        //     } else {
        //         $('#modalInformation').modal('hide')
        //     }
        // }

        $('document').ready(function() {
            $('body').on('click', '#rememberLater', function() {
                updateStatusInformation()
            })

            $('#modalInformation').modal('show')
            getInformation();
        })
    </script>
@endpush
