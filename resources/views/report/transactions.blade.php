@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <h4 class="fw-bold">{{ $title ?? '' }}</h4>
            </div>
        </div>
    </div>

    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Print Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12 mb-3">
                        <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD"
                            id="flatpickr-range" />
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <button class="btn btn-danger"><i class="tf-icons ti ti-file-text me-2"></i> PDF</button>
                        <button class="btn btn-success"><i class="tf-icons ti ti-file-spreadsheet me-2"></i> Excel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="mb-3 col-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Statistik Transaksi</h5>
                    <small class="text-muted">Statistik Transaksi Per Bulan ini</small>
                </div>
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-calendar"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Hari ini</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Kemarin</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">7 Hari
                                Terakhir</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">30 hari
                                terakhir</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Bulan ini</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Tahun ini</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                <i class="ti ti-chart-pie-2 ti-sm"></i>
                            </div>
                            <div class="card-primary">
                                <h5 class="mb-0">10</h5>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-warning me-3 p-2">
                                <i class="ti ti-hourglass-empty ti-sm"></i>
                            </div>
                            <div class="card-warning">
                                <h5 class="mb-0">10</h5>
                                <small>Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                                <i class="ti ti-checks ti-sm"></i>
                            </div>
                            <div class="card-success">
                                <h5 class="mb-0">10</h5>
                                <small>Berhasil</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                <i class="ti ti-circle-off ti-sm"></i>
                            </div>
                            <div class="card-danger">
                                <h5 class="mb-0">10</h5>
                                <small>Gagal</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Statistics -->

    <!-- Keuangan -->
    <div class="mb-3 col-12">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Statistik Keuangan</h5>
                    <small class="text-muted">Statistik Keuangan Per Bulan ini</small>
                </div>
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-calendar"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Hari ini</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Kemarin</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">7 Hari
                                Terakhir</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">30 hari
                                terakhir</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Bulan ini</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Tahun ini</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                <i class="ti ti-building-bank ti-sm"></i>
                            </div>
                            <div class="card-primary">
                                <h5 class="mb-0">10</h5>
                                <small>Saldo Terpakai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-success me-3 p-2">
                                <i class="ti ti-coins ti-sm"></i>
                            </div>
                            <div class="card-success">
                                <h5 class="mb-0">10</h5>
                                <small>Penghasilan Bersih</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded-pill bg-label-warning me-3 p-2">
                                <i class="ti ti-currency-dollar ti-sm"></i>
                            </div>
                            <div class="card-warning">
                                <h5 class="mb-0">10</h5>
                                <small>Penghasilan Kotor</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Keuangan -->

    <!-- Line Area Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h5 class="card-title mb-0">Grafik Transaksi</h5>
                    <small class="text-muted">Grafik Transaksi Per Bulan ini</small>
                </div>
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ti ti-calendar"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Hari ini</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Kemarin</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">7 Hari
                                Terakhir</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">30 hari
                                terakhir</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Bulan ini</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Tahun ini</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div id="lineAreaChartTransaction"></div>
            </div>
        </div>
    </div>
    <!-- /Line Area Chart -->
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/charts-transactions.js') }}"></script>
    <script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
@endpush
