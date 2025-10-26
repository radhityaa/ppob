@extends('layouts.administrator.app')

@section('title', 'Upgrade Level')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            Upgrade Level
                        </h1>
                        <p class="text-muted mb-0">Tingkatkan level Anda untuk mendapatkan harga yang lebih murah</p>
                    </div>
                    <div>
                        <span class="badge bg-info fs-6">
                            <i class="ti ti-wallet ti-xs me-1"></i>Saldo: Rp {{ number_format(Auth::user()->saldo) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <!-- Current Level Info -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-user ti-xs me-2 text-white"></i>
                            <span class="text-white">Level Saat Ini</span>
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @if ($currentLevel)
                            <div class="my-3">
                                <div class="level-badge level-{{ $currentLevel->name }} mb-2">
                                    <i class="ti ti-crown ti-2x"></i>
                                </div>
                                <h4
                                    class="fw-bold text-{{ $currentLevel->name === 'member' ? 'primary' : ($currentLevel->name === 'reseller' ? 'success' : 'info') }}">
                                    {{ $currentLevel->display_name }}
                                </h4>
                                <p class="text-muted mb-0">{{ $currentLevel->description }}</p>
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="level-badge level-member mb-2">
                                    <i class="ti ti-user ti-2x"></i>
                                </div>
                                <h4 class="fw-bold text-primary">Member</h4>
                                <p class="text-muted mb-0">Level default untuk semua user</p>
                            </div>
                        @endif

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Level:</span>
                                <strong>{{ $currentLevel ? $currentLevel->display_name : 'Member' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Upgrade:</span>
                                <strong class="text-success">
                                    @if ($canUpgrade)
                                        Tersedia
                                    @else
                                        Maksimal
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upgrade Options -->
            <div class="col-lg-8">
                @if ($canUpgrade)
                    <div class="card mb-4 shadow">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-arrow-up ti-xs me-2"></i>Upgrade ke Level Berikutnya
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($nextLevel)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3 text-center">
                                            <div class="level-badge level-{{ $nextLevel->name }} mb-2">
                                                <i class="ti ti-crown ti-2x"></i>
                                            </div>
                                            <h5
                                                class="fw-bold text-{{ $nextLevel->name === 'member' ? 'primary' : ($nextLevel->name === 'reseller' ? 'success' : 'info') }}">
                                                {{ $nextLevel->display_name }}
                                            </h5>
                                            <p class="text-muted">{{ $nextLevel->description }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="upgrade-info">
                                            <h6 class="text-muted mb-3">Informasi Upgrade</h6>
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <span>Harga Upgrade:</span>
                                                    <strong class="text-primary">Rp
                                                        {{ number_format($upgradePrice) }}</strong>
                                                </div>
                                            </div>
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <span>Saldo Anda:</span>
                                                    <strong class="text-success">Rp
                                                        {{ number_format(Auth::user()->saldo) }}</strong>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between">
                                                    <span>Saldo Setelah:</span>
                                                    <strong
                                                        class="text-{{ Auth::user()->saldo - $upgradePrice >= 0 ? 'success' : 'danger' }}">
                                                        Rp {{ number_format(Auth::user()->saldo - $upgradePrice) }}
                                                    </strong>
                                                </div>
                                            </div>

                                            @if (Auth::user()->saldo >= $upgradePrice)
                                                <form action="{{ route('level-upgrade.store') }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin upgrade level?')">
                                                    @csrf
                                                    <input type="hidden" name="to_level_id" value="{{ $nextLevel->id }}">
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="ti ti-arrow-up ti-xs me-1"></i>Upgrade Sekarang
                                                    </button>
                                                </form>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="ti ti-alert-circle ti-xs me-1"></i>
                                                    Saldo tidak mencukupi untuk upgrade level.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="py-4 text-center">
                                    <i class="ti ti-crown ti-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Anda sudah mencapai level tertinggi!</h5>
                                    <p class="text-muted">Tidak ada level yang lebih tinggi untuk diupgrade.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- All Levels Overview -->
                <div class="card mb-4 shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-list ti-xs me-2"></i>Semua Level
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($levels as $level)
                                @php
                                    $isCurrentLevel = $currentLevel && $currentLevel->id === $level->id;
                                    $isNextLevel = $nextLevel && $nextLevel->id === $level->id;
                                    $isLowerLevel = $currentLevel && $level->sort_order < $currentLevel->sort_order;
                                @endphp
                                <div class="col-md-4 mb-3">
                                    <div
                                        class="card {{ $isCurrentLevel ? 'border-primary' : '' }} {{ $isNextLevel ? 'border-success' : '' }} {{ $isLowerLevel ? 'opacity-50' : '' }}">
                                        <div class="card-body text-center">
                                            <div
                                                class="level-badge level-{{ $level->name }} {{ $isLowerLevel ? 'opacity-50' : '' }} mb-2">
                                                <i class="ti ti-crown ti-2x"></i>
                                            </div>
                                            <h6
                                                class="fw-bold text-{{ $level->name === 'member' ? 'primary' : ($level->name === 'reseller' ? 'success' : 'info') }} {{ $isLowerLevel ? 'text-muted' : '' }}">
                                                {{ $level->display_name }}
                                            </h6>
                                            <p class="text-muted small">{{ $level->description }}</p>
                                            @if ($isCurrentLevel)
                                                <span class="badge bg-primary">Level Saat Ini</span>
                                            @elseif($isNextLevel)
                                                <span class="badge bg-success">Level Berikutnya</span>
                                            @elseif($isLowerLevel)
                                                <span class="badge bg-secondary">Level Terlalu Rendah</span>
                                            @else
                                                <span class="badge bg-light text-dark">Level Tersedia</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Upgrade History -->
                @if ($upgradeHistory->count() > 0)
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="ti ti-history ti-xs me-2"></i>Riwayat Upgrade
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-sm table">
                                    <thead>
                                        <tr>
                                            <th>Dari</th>
                                            <th>Ke</th>
                                            <th>Harga</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($upgradeHistory as $upgrade)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $upgrade->fromLevel ? $upgrade->fromLevel->display_name : 'Member' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ $upgrade->toLevel->display_name }}
                                                    </span>
                                                </td>
                                                <td>Rp {{ number_format($upgrade->upgrade_price) }}</td>
                                                <td>{{ $upgrade->created_at->format('d M Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('page-css')
    <style>
        .level-badge {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .level-badge.level-member {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .level-badge.level-reseller {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .level-badge.level-agen {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .upgrade-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }

        .card.border-primary {
            border-width: 2px !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .card.border-success {
            border-width: 2px !important;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .card.opacity-50 {
            opacity: 0.5;
        }

        .level-badge.opacity-50 {
            opacity: 0.5;
        }
    </style>
@endpush
