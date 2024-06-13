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
    <div class="col-xl-4 mb-4 col-lg-5 col-12" style="padding-left: 0px; padding-right: 0px;">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Selamat Datang! 🎉</h5>
                        <p class="mb-2 text-truncate">{{ Auth::user()->name }}</p>
                        <h4 class="text-primary mb-1">Rp {{ number_format(Auth::user()->saldo, 0, '.', '.') }}</h4>
                        <a href="{{ route('deposit.index') }}" class="btn btn-primary">Isi Saldo</a>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="140" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile -->

    <!-- Statistics -->
    <div class="col-xl-8 mb-4 col-lg-7 col-12">
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
                                <h5 class="mb-0">230k</h5>
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
                                <h5 class="mb-0">8.549k</h5>
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
                                <h5 class="mb-0">1.423k</h5>
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
                                <h5 class="mb-0">$9745</h5>
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
        <div class="card p-3 mb-4">
            <p class="h5 font-weight-semibold mb-3 card-title dark:text-white">{{ $listService->title }}</p>
            <div class="w-100" style="height: 1px; background-color: #e0e0e0;"></div>
            <div class="row">
                @foreach ($listService->rechargeItems as $rechargeItem)
                    <div class="col-4 col-md-2 d-flex flex-column align-items-center">
                        <a href="{{ route($rechargeItem->route) }}"
                            class="d-block py-4 text-center flex-grow-1 d-flex flex-column align-items-center justify-content-between">
                            <img src="{{ asset('assets/img/services/' . $rechargeItem->src) }}"
                                class="img-fluid w-50 w-lg-20 mb-2">
                            <p class="card-title dark:text-white mb-0">{{ $rechargeItem->label }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    {{-- /Menu Service --}}

    <!-- Transaction table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Riwayat Transaksi Hari Ini</h5>
        </div>
        <div class="table-responsive card-datatable">
            <table class="table datatable-invoice border-top">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th><i class="ti ti-trending-up text-secondary"></i></th>
                        <th>Total</th>
                        <th>Issued Date</th>
                        <th>Invoice Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- /Transaction table -->
@endsection
