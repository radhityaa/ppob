@extends('layouts.administrator.app')

@section('title', 'Admin Report')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="ti ti-chart-bar ti-xs me-2"></i>Report Admin
                        </h1>
                        <p class="text-muted mb-0">Laporan penghasilan bersih dan kotor, deposit, dan analisis bisnis</p>
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
                        <form method="GET" action="{{ route('admin.report.index') }}" class="row g-3">
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
                                    Total Revenue
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['transactions']['total_revenue']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-arrow-up ti-xs me-1"></i>
                                    {{ $reports['transactions']['total_transactions'] }} transaksi
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-currency-dollar ti-2x text-gray-300"></i>
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
                                    Gross Profit
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['profit']['gross_profit']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-percentage ti-xs me-1"></i>
                                    {{ number_format($reports['profit']['profit_margin'], 2) }}% margin
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-trending-up ti-2x text-gray-300"></i>
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
                                    Total Deposits
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['financial']['total_deposits']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-arrow-up ti-xs me-1"></i>
                                    {{ $reports['deposits']['approved_deposits'] }} approved
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
                <div class="card border-left-warning h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-warning text-uppercase mb-1 text-xs">
                                    Net Cash Flow
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($reports['financial']['net_cash_flow']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-arrow-up ti-xs me-1"></i>
                                    Deposits - Withdrawals
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-wallet ti-2x text-gray-300"></i>
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
                            <i class="ti ti-chart-pie ti-xs me-2"></i>Analisis Transaksi
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
                                        <span class="text-muted">Total Revenue:</span>
                                        <span class="font-weight-bold text-success">Rp
                                            {{ number_format($reports['transactions']['total_revenue']) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Total Cost:</span>
                                        <span class="font-weight-bold text-danger">Rp
                                            {{ number_format($reports['transactions']['total_cost']) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Gross Profit:</span>
                                        <span class="font-weight-bold text-primary">Rp
                                            {{ number_format($reports['transactions']['total_profit']) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Avg Transaction:</span>
                                        <span class="font-weight-bold">Rp
                                            {{ number_format($reports['transactions']['average_transaction']) }}</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Profit Margin:</span>
                                        <span
                                            class="font-weight-bold text-info">{{ number_format($reports['profit']['profit_margin'], 2) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit by User Type -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-users ti-xs me-2"></i>Profit by User Type
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Member Profit:</span>
                                <span class="font-weight-bold text-success">Rp
                                    {{ number_format($reports['profit']['profit_by_user_type']['member']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Reseller Profit:</span>
                                <span class="font-weight-bold text-info">Rp
                                    {{ number_format($reports['profit']['profit_by_user_type']['reseller']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Agen Profit:</span>
                                <span class="font-weight-bold text-warning">Rp
                                    {{ number_format($reports['profit']['profit_by_user_type']['agen']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by Product Type -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-package ti-xs me-2"></i>Revenue by Product Type
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-bordered table">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Jumlah Transaksi</th>
                                        <th>Revenue</th>
                                        <th>Cost</th>
                                        <th>Profit</th>
                                        <th>Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports['transactions']['revenue_by_type'] as $category => $data)
                                        <tr>
                                            <td>{{ $category }}</td>
                                            <td>{{ number_format($data['count']) }}</td>
                                            <td class="text-success">Rp {{ number_format($data['revenue']) }}</td>
                                            <td class="text-danger">Rp {{ number_format($data['cost']) }}</td>
                                            <td class="text-primary">Rp {{ number_format($data['profit']) }}</td>
                                            <td class="text-info">
                                                {{ $data['revenue'] > 0 ? number_format(($data['profit'] / $data['revenue']) * 100, 2) : 0 }}%
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
            <!-- User Statistics -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-users ti-xs me-2"></i>User Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">New Users:</span>
                                <span class="font-weight-bold">{{ number_format($reports['users']['new_users']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Active Users:</span>
                                <span
                                    class="font-weight-bold">{{ number_format($reports['users']['active_users']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Users:</span>
                                <span
                                    class="font-weight-bold">{{ number_format($reports['users']['total_users']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deposit Statistics -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-credit-card ti-xs me-2"></i>Deposit Statistics
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Deposits:</span>
                                <span
                                    class="font-weight-bold">{{ number_format($reports['deposits']['total_deposits']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Approved:</span>
                                <span
                                    class="font-weight-bold text-success">{{ number_format($reports['deposits']['approved_deposits']) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Pending:</span>
                                <span
                                    class="font-weight-bold text-warning">{{ number_format($reports['deposits']['pending_deposits']) }}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Rejected:</span>
                                <span
                                    class="font-weight-bold text-danger">{{ number_format($reports['deposits']['rejected_deposits']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Statistics -->
            <div class="col-xl-4 col-lg-12 mb-4">
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
                    label: 'Revenue',
                    data: @json(collect($reports['daily_summary'])->pluck('revenue')),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Profit',
                    data: @json(collect($reports['daily_summary'])->pluck('profit')),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Deposits',
                    data: @json(collect($reports['daily_summary'])->pluck('deposits')),
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

            fetch(`{{ route('admin.report.export') }}?date_from=${dateFrom}&date_to=${dateTo}`)
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
