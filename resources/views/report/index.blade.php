@extends('layouts.administrator.app')

@section('title', 'Laporan Saya')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="ti ti-chart-bar ti-xs me-2"></i>Laporan Saya
                        </h1>
                        <p class="text-muted mb-0">Laporan transaksi, deposit, dan aktivitas Anda</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-success btn-sm" onclick="exportReport()">
                            <i class="ti ti-download ti-xs me-1"></i>Export
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshReport()">
                            <i class="ti ti-refresh ti-xs me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <form method="GET" action="{{ route('report.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="date_from" class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" id="date_from" name="date_from"
                                    value="{{ $dateFrom }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="date_to" class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="date_to" name="date_to"
                                    value="{{ $dateTo }}" required>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-filter ti-xs me-1"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-primary text-uppercase mb-1 text-xs">
                                    Saldo Saat Ini
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['financial']['current_balance']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-wallet ti-xs me-1"></i>
                                    Saldo terakhir
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-wallet ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-success text-uppercase mb-1 text-xs">
                                    Total Deposit
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['financial']['total_deposits']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-arrow-up ti-xs me-1"></i>
                                    {{ $reports['financial']['pending_deposits'] > 0 ? 'Rp ' . number_format($reports['financial']['pending_deposits']) . ' pending' : 'Semua approved' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-credit-card ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-info text-uppercase mb-1 text-xs">
                                    Total Pengeluaran
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['financial']['total_spent']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-arrow-down ti-xs me-1"></i>
                                    {{ $reports['transactions']['total_transactions'] }} transaksi
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-shopping-cart ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-warning text-uppercase mb-1 text-xs">
                                    Net Flow
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['financial']['net_flow']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-trending-up ti-xs me-1"></i>
                                    Deposit - Pengeluaran
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-chart-line ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Reports -->
        <div class="row mb-4">
            <!-- Transaction Analysis -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-shopping-cart ti-xs me-2"></i>Analisis Transaksi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Total Transaksi:</span>
                                        <span
                                            class="font-weight-bold">{{ number_format($reports['transactions']['total_transactions']) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Berhasil:</span>
                                        <span
                                            class="font-weight-bold text-success">{{ number_format($reports['transactions']['successful_transactions']) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Gagal:</span>
                                        <span
                                            class="font-weight-bold text-danger">{{ number_format($reports['transactions']['failed_transactions']) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Success Rate:</span>
                                        <span
                                            class="font-weight-bold text-primary">{{ number_format($reports['transactions']['success_rate'], 2) }}%</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Rata-rata Transaksi:</span>
                                        <span class="font-weight-bold">Rp
                                            {{ number_format($reports['transactions']['average_transaction']) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Total Pengeluaran:</span>
                                        <span class="font-weight-bold text-info">Rp
                                            {{ number_format($reports['transactions']['total_spent']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mutation Analysis -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-moneybag ti-xs me-2"></i>Analisis Mutasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Mutasi:</span>
                                <span
                                    class="font-weight-bold">{{ number_format($reports['mutations']['total_mutations']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Debit:</span>
                                <span class="font-weight-bold text-danger">Rp
                                    {{ number_format($reports['mutations']['total_debit']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Kredit:</span>
                                <span class="font-weight-bold text-success">Rp
                                    {{ number_format($reports['mutations']['total_credit']) }}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Net Mutasi:</span>
                                <span class="font-weight-bold text-primary">Rp
                                    {{ number_format($reports['mutations']['net_mutation']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions by Product Type -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-package ti-xs me-2"></i>Transaksi by Kategori
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-bordered table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Jumlah Transaksi</th>
                                        <th>Total Pengeluaran</th>
                                        <th>Berhasil</th>
                                        <th>Gagal</th>
                                        <th>Success Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports['transactions']['transactions_by_type'] as $category => $data)
                                        <tr>
                                            <td>{{ $category }}</td>
                                            <td>{{ number_format($data['count']) }}</td>
                                            <td class="text-info">Rp {{ number_format($data['total_spent']) }}</td>
                                            <td class="text-success">{{ number_format($data['successful']) }}</td>
                                            <td class="text-danger">{{ number_format($data['failed']) }}</td>
                                            <td class="text-primary">
                                                {{ $data['count'] > 0 ? number_format(($data['successful'] / $data['count']) * 100, 2) : 0 }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daily Summary Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-chart-line ti-xs me-2"></i>Daily Summary
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="row">
            <!-- Ticket Statistics -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-ticket ti-xs me-2"></i>Ticket Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Tickets:</span>
                                <span
                                    class="font-weight-bold">{{ number_format($reports['tickets']['total_tickets']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Open:</span>
                                <span class="badge bg-primary">{{ $reports['tickets']['open_tickets'] }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">In Progress:</span>
                                <span class="badge bg-warning">{{ $reports['tickets']['in_progress_tickets'] }}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Resolved:</span>
                                <span class="badge bg-success">{{ $reports['tickets']['resolved_tickets'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mutation by Type -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-list ti-xs me-2"></i>Mutasi by Jenis
                        </h6>
                    </div>
                    <div class="card-body">
                        @forelse($reports['mutations']['mutations_by_type'] as $type => $data)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">{{ $type }}:</span>
                                    <div>
                                        <span class="text-success me-2">+Rp {{ number_format($data['credit']) }}</span>
                                        <span class="text-danger">-Rp {{ number_format($data['debit']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-4 text-center">
                                <i class="ti ti-list ti-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada data mutasi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-css')
    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
        }
    </style>
@endpush

@push('page-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Daily Chart
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        const dailyChart = new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: @json(collect($reports['daily_summary'])->pluck('date')),
                datasets: [{
                    label: 'Pengeluaran',
                    data: @json(collect($reports['daily_summary'])->pluck('spent')),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Deposit',
                    data: @json(collect($reports['daily_summary'])->pluck('deposits')),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Net Flow',
                    data: @json(collect($reports['daily_summary'])->pluck('net_flow')),
                    borderColor: 'rgb(255, 205, 86)',
                    backgroundColor: 'rgba(255, 205, 86, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Export report
        function exportReport() {
            const dateFrom = document.getElementById('date_from').value;
            const dateTo = document.getElementById('date_to').value;

            fetch(`{{ route('report.export') }}?date_from=${dateFrom}&date_to=${dateTo}`)
                .then(response => response.json())
                .then(data => {
                    alert('Export functionality will be implemented');
                });
        }

        // Refresh report
        function refreshReport() {
            location.reload();
        }
    </script>
@endpush
