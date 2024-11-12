@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@section('content')
    <div class="col-md-12">
        <div class="d-md-flex justify-content-between align-items-center">
            <h4 class="fw-bold">{{ $title ?? '' }}</h4>
        </div>
    </div>

    <!-- Profit Total -->
    <div class="col-xl-3 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Profit</h5>
                <small class="text-muted">Total</small>
            </div>
            <div class="card-body">
                <div id="profitLastDay"></div>
                <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                    <h4 class="mb-0">{{ number_format($user['profit_reseller'] ?? 0, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Hari Terakhir -->
    <div class="col-xl-3 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Profit</h5>
                <small class="text-muted">Hari Terakhir</small>
            </div>
            <div class="card-body">
                <div id="profitLastDay"></div>
                <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                    <h4 class="mb-0">{{ number_format($statistics['daily']['total'] ?? 0, 0, ',', '.') }}</h4>
                    <small
                        class="{{ $statistics['daily']['percentage_change'] ?? 0 >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $statistics['daily']['percentage_change'] ?? 0 >= 0 ? '+' : '' }}{{ number_format($statistics['daily']['percentage_change'] ?? 0, 2) }}%
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Minggu Lalu -->
    <div class="col-xl-3 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Profit</h5>
                <small class="text-muted">Minggu Lalu</small>
            </div>
            <div class="card-body">
                <div id="profitLastWeek"></div>
                <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                    <h4 class="mb-0">{{ number_format($statistics['weekly']['total'] ?? 0, 0, ',', '.') }}</h4>
                    <small
                        class="{{ $statistics['weekly']['percentage_change'] ?? 0 >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $statistics['weekly']['percentage_change'] ?? 0 >= 0 ? '+' : '' }}{{ number_format($statistics['weekly']['percentage_change'] ?? 0, 2) }}%
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Bulan Lalu -->
    <div class="col-xl-3 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Profit</h5>
                <small class="text-muted">Bulan Lalu</small>
            </div>
            <div class="card-body">
                <div id="profitLastMonth"></div>
                <div class="d-flex justify-content-between align-items-center mt-3 gap-3">
                    <h4 class="mb-0">{{ number_format($statistics['monthly']['total'] ?? 0, 0, ',', '.') }}</h4>
                    <small
                        class="{{ $statistics['monthly']['percentage_change'] ?? 0 >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $statistics['monthly']['percentage_change'] ?? 0 >= 0 ? '+' : '' }}{{ number_format($statistics['monthly']['percentage_change'] ?? 0, 2) }}%
                    </small>
                </div>
            </div>
        </div>
    </div>


    <!-- List Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="dataTable border-top table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profit Didapat</th>
                        <th>Agen</th>
                        <th>Pembelian</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var table = $('.dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('profits.index') }}",
            columnDefs: [{
                // For Responsive
                className: 'control',
                orderable: false,
                searchable: false,
                responsivePriority: 3,
                targets: 0,
                render: function(data, type, full, meta) {
                    return '';
                }
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'total_profit',
                    name: 'total_profit'
                },
                {
                    data: 'agen',
                    name: 'agen'
                },
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                }
            ],
            responsive: true,
        })
    </script>
@endpush
