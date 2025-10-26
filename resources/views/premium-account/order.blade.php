@extends('layouts.administrator.app')

@section('title', 'Pemesanan ' . $service->game)

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            </i>Pemesanan {{ $service->game }}
                        </h1>
                        <p class="text-muted mb-0">Lengkapi form di bawah ini untuk memesan layanan premium</p>
                    </div>
                    <div>
                        <a href="{{ route('premium-account.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left ti-xs me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="ti ti-check-circle ti-xs me-1"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="ti ti-x-circle ti-xs me-1"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <!-- Service Info -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-gradient-primary text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-info-circle ti-xs me-2 text-white"></i>
                            <span class="text-white">Informasi Layanan</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="my-3 text-center">
                            @php
                                $iconPath = asset("assets/img/products/{$service->game}.png");
                            @endphp
                            <img src="{{ $iconPath }}" alt="{{ $service->game }} logo" class="mb-2"
                                style="width:64px;height:64px;object-fit:contain;background:#fff;border-radius:8px;padding:4px;">
                            <h6 class="fw-bold">{{ $service->game }}</h6>
                            <p class="text-muted mb-0">{{ $service->name }}</p>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Kode:</span>
                                <strong>{{ $service->code }}</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Server:</span>
                                <strong>{{ $service->server ?? 'N/A' }}</strong>
                            </div>
                        </div>

                        @if (isset($formFields['note']))
                            <div class="alert alert-danger">
                                <i class="ti ti-info-circle ti-xs me-1"></i> Perhatian!
                                <ul class="mb-0 ps-3" style="font-size:13px;">
                                    @if (isset($formFields['note']) && is_array($formFields['note']))
                                        @foreach ($formFields['note'] as $note)
                                            <li>{{ $note }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endif
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle ti-xs me-1"></i>
                            <small>Pastikan data yang Anda masukkan sudah benar. Admin akan memproses pesanan Anda dalam
                                1x24 jam.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Form -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-edit ti-xs me-2"></i>Form Pemesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">Terjadi kesalahan:</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('premium-order.store', $service->id) }}" method="POST">
                            @csrf
                            <!-- Service-Specific Fields -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">Informasi Akun</h6>
                                <div class="row">
                                    @foreach ($formFields['specific'] as $fieldName => $fieldConfig)
                                        <div class="col-md-6 mb-3">
                                            <label for="{{ $fieldName }}" class="form-label">
                                                {{ $fieldConfig['label'] }}
                                                @if ($fieldConfig['required'])
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            @if ($fieldConfig['type'] == 'select')
                                                <select class="form-control @error($fieldName) is-invalid @enderror"
                                                    id="{{ $fieldName }}" name="{{ $fieldName }}"
                                                    {{ $fieldConfig['required'] ? 'required' : '' }}>
                                                    <option value="">Pilih {{ $fieldConfig['label'] }}</option>
                                                    @foreach ($fieldConfig['options'] as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old($fieldName) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="{{ $fieldConfig['type'] }}"
                                                    class="form-control @error($fieldName) is-invalid @enderror"
                                                    id="{{ $fieldName }}" name="{{ $fieldName }}"
                                                    value="{{ old($fieldName) }}"
                                                    placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                                    {{ $fieldConfig['required'] ? 'required' : '' }}>
                                            @endif

                                            @error($fieldName)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                                @if (isset($formFields['information']))
                                    <div class="mb-3">
                                        <small class="text-muted">{{ $formFields['information'] }}</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Price Summary -->
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Daftar Harga</h6>
                                <div class="row g-2">
                                    <div class="col-12 col-md-4">
                                        <div
                                            class="d-flex justify-content-between align-items-center position-relative {{ Auth::user()->hasRole('member') ? 'border-primary' : 'border-secondary' }} h-100 rounded border p-2">
                                            @if (Auth::user()->hasRole('member'))
                                                <div class="position-absolute start-50 translate-middle top-0">
                                                    <span class="badge bg-primary">Level Anda</span>
                                                </div>
                                            @endif
                                            <span>Member:</span>
                                            <strong class="text-primary">Rp
                                                {{ number_format($service->price_member) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div
                                            class="d-flex justify-content-between align-items-center position-relative {{ Auth::user()->hasRole('reseller') ? 'border-success' : 'border-secondary' }} h-100 rounded border p-2">
                                            @if (Auth::user()->hasRole('reseller'))
                                                <div class="position-absolute start-50 translate-middle top-0">
                                                    <span class="badge bg-success">Level Anda</span>
                                                </div>
                                            @endif
                                            <span>Reseller:</span>
                                            <strong class="text-success">Rp
                                                {{ number_format($service->price_reseller) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div
                                            class="d-flex justify-content-between align-items-center position-relative {{ Auth::user()->hasRole('agen') ? 'border-info' : 'border-secondary' }} h-100 rounded border p-2">
                                            @if (Auth::user()->hasRole('agen'))
                                                <div class="position-absolute start-50 translate-middle top-0">
                                                    <span class="badge bg-info">Level Anda</span>
                                                </div>
                                            @endif
                                            <span>Agen:</span>
                                            <strong class="text-info">Rp
                                                {{ number_format($service->price_agen) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Saya menyetujui <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#termsModal">syarat dan ketentuan</a> yang berlaku
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('premium-account.index') }}" class="btn btn-outline-secondary">
                                    <i class="ti ti-arrow-left ti-xs me-1"></i>Batal
                                </a>
                                @if ($service->status)
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-shopping-cart ti-xs me-1"></i>Pesan Sekarang
                                    </button>
                                @else
                                    <button class="btn btn-secondary" disabled>
                                        <i class="ti ti-clock ti-xs me-1"></i>Tidak Tersedia
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Syarat dan Ketentuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>Ketentuan Umum:</h6>
                    <ul>
                        <li>Akun premium akan aktif/dikirimkan dalam 1x24 jam setelah pembayaran dikonfirmasi</li>
                        <li>Durasi layanan dimulai dari tanggal aktivasi akun/pengiriman akun</li>
                        <li>Pengguna bertanggung jawab atas keamanan akun yang diberikan</li>
                        <li>Layanan dapat dihentikan jika melanggar ketentuan</li>
                    </ul>

                    <h6>Kebijakan Pengembalian:</h6>
                    <ul>
                        <li>Pengembalian hanya berlaku jika akun tidak dapat diakses dalam 7 hari setelah pembayaran</li>
                        <li>Pengembalian tidak berlaku untuk pelanggaran ketentuan</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-css')
    <style>
        /* Role indicator styling for order page */
        .position-relative .badge {
            font-size: 0.6rem;
            padding: 0.25rem 0.5rem;
            z-index: 10;
        }

        .border-primary {
            border-width: 2px !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .border-success {
            border-width: 2px !important;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .border-info {
            border-width: 2px !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 202, 240, 0.25);
        }
    </style>
@endpush
