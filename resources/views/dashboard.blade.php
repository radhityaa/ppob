@extends('layouts.administrator.app')

@push('page-css')
    <style>
        @media (min-width: 992px) {
            .w-lg-20 {
                width: 20% !important;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Profile -->
    <div class="col-xl-4 col-lg-5 col-12 mb-4" style="padding-left: 0px; padding-right: 0px;">
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
    {{-- /Menu Service --}}

    <!-- Modal News -->
    <div class="modal fade" id="modalNews" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNewsTitle">Berita Terbaru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla facilisi. Sed sed lectus vel
                        risus
                        ultricies faucibus. Duis euismod, neque vel vestibulum elementum, arcu neque gravida massa, a
                        malesuada lectus nisi id lectus.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="rememberLater">Ingatkan Nanti</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        function updateStatusNews() {
            $.ajax({
                url: '{{ route('news.update.user') }}',
                type: 'POST',
                data: {
                    userId: '{{ Auth::user()->id }}',
                },
                success: function(res) {
                    if (res.success) {
                        $('#modalNews').modal('hide')
                    }
                }
            })
        }

        function checkNews() {
            if ({{ $showNewsModal }}) {
                $('#modalNews').modal('show')
            }
        }

        $('document').ready(function() {
            $('body').on('click', '#rememberLater', function() {
                updateStatusNews()
            })

            checkNews()
        })
    </script>
@endpush
