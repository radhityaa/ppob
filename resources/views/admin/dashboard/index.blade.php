@extends('layouts.administrator.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="ti ti-dashboard ti-xs me-2"></i>Admin Dashboard
                        </h1>
                        <p class="text-muted mb-0">Monitoring sistem dan aktivitas pengguna</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                            <i class="ti ti-refresh ti-xs me-1"></i>Refresh
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="exportData()">
                            <i class="ti ti-download ti-xs me-1"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Users Stats -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-primary text-uppercase mb-1 text-xs">
                                    Total Users
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    {{ number_format($stats['total_users']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-arrow-up ti-xs me-1"></i>
                                    {{ $stats['new_users_today'] }} hari ini
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-users ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deposits Stats -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-success text-uppercase mb-1 text-xs">
                                    Total Deposits
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    Rp {{ number_format($stats['total_deposit_amount']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-arrow-up ti-xs me-1"></i>
                                    {{ $stats['deposits_today'] }} hari ini
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-credit-card ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Stats -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-info text-uppercase mb-1 text-xs">
                                    Total Products
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    {{ number_format($stats['total_products']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-check ti-xs me-1"></i>
                                    {{ $stats['active_products'] }} aktif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-package ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets Stats -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning h-100 py-2 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="font-weight-bold text-warning text-uppercase mb-1 text-xs">
                                    Total Tickets
                                </div>
                                <div class="h5 font-weight-bold mb-0 text-gray-800">
                                    {{ number_format($stats['total_tickets']) }}
                                </div>
                                <div class="text-muted text-xs">
                                    <i class="ti ti-alert-circle ti-xs me-1"></i>
                                    {{ $stats['open_tickets'] }} terbuka
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="ti ti-ticket ti-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="row mb-4">
            <!-- Financial Overview -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-chart-pie ti-xs me-2"></i>Financial Overview
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Pending Deposits:</span>
                                <span class="font-weight-bold text-warning">
                                    Rp {{ number_format($stats['pending_deposits']) }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Approved Deposits:</span>
                                <span class="font-weight-bold text-success">
                                    Rp {{ number_format($stats['approved_deposits']) }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Rejected Deposits:</span>
                                <span class="font-weight-bold text-danger">
                                    Rp {{ number_format($stats['rejected_deposits']) }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">This Month:</span>
                                <span class="font-weight-bold text-primary">
                                    Rp {{ number_format($stats['deposit_amount_this_month']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Status -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-ticket ti-xs me-2"></i>Ticket Status
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Open:</span>
                                <span class="badge bg-primary">{{ $stats['open_tickets'] }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">In Progress:</span>
                                <span class="badge bg-warning">{{ $stats['in_progress_tickets'] }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Resolved:</span>
                                <span class="badge bg-success">{{ $stats['resolved_tickets'] }}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Closed:</span>
                                <span class="badge bg-secondary">{{ $stats['closed_tickets'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Health -->
            <div class="col-xl-4 col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-heart ti-xs me-2"></i>System Health
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Database:</span>
                                <span
                                    class="badge bg-{{ $systemHealth['database_status']['status'] === 'healthy' ? 'success' : 'danger' }}">
                                    {{ $systemHealth['database_status']['status'] }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Storage:</span>
                                <span
                                    class="badge bg-{{ $systemHealth['storage_status']['status'] === 'healthy' ? 'success' : 'warning' }}">
                                    {{ $systemHealth['storage_status']['percentage'] }}%
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Last Sync:</span>
                                <span class="text-muted">{{ $systemHealth['last_sync'] }}</span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Errors:</span>
                                <span class="text-muted">{{ $systemHealth['error_count'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Activities -->
        <div class="row">
            <!-- Chart -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-chart-line ti-xs me-2"></i>Activity Overview (30 Days)
                        </h6>
                    </div>
                    <div class="card-body">
                        <canvas id="activityChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="font-weight-bold text-primary m-0">
                            <i class="ti ti-activity ti-xs me-2"></i>Recent Activities
                        </h6>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($recentActivities as $activity)
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="bg-{{ $activity['type'] === 'deposit' ? 'success' : ($activity['type'] === 'ticket' ? 'warning' : 'info') }} rounded-circle p-2">
                                        <i class="{{ $activity['icon'] }} ti-xs text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold text-dark">{{ $activity['title'] }}</div>
                                    <div class="text-muted small">{{ $activity['description'] }}</div>
                                    <div class="text-muted small">
                                        <i class="ti ti-clock ti-xs me-1"></i>
                                        {{ $activity['time']->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-4 text-center">
                                <i class="ti ti-activity ti-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada aktivitas terbaru</p>
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
        // Activity Chart
        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Deposits (Rp)',
                    data: @json($chartData['deposits']),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Users',
                    data: @json($chartData['users']),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.1
                }, {
                    label: 'Tickets',
                    data: @json($chartData['tickets']),
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

        // Refresh dashboard
        function refreshDashboard() {
            location.reload();
        }

        // Export data
        function exportData() {
            // Implement export functionality
            alert('Export functionality will be implemented');
        }

        // Auto refresh every 5 minutes
        setInterval(function() {
            // Refresh chart data
            fetch('/admin/dashboard/data?type=charts')
                .then(response => response.json())
                .then(data => {
                    activityChart.data.labels = data.labels;
                    activityChart.data.datasets[0].data = data.deposits;
                    activityChart.data.datasets[1].data = data.users;
                    activityChart.data.datasets[2].data = data.tickets;
                    activityChart.update();
                });
        }, 300000); // 5 minutes
    </script>
@endpush
