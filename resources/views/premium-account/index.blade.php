@extends('layouts.administrator.app')

@section('title', 'Premium Account')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            Akun Premium
                        </h1>
                        <p class="text-muted mb-0">Daftar harga akun premium berbagai layanan</p>
                    </div>
                    <div class="d-flex gap-2">
                        @role('admin')
                            <button class="btn btn-success btn-sm" onclick="syncData()">
                                <i class="ti ti-refresh ti-xs me-1"></i>Sync Data
                            </button>
                        @endrole
                        <button class="btn btn-outline-primary btn-sm" onclick="refreshPage()">
                            <i class="ti ti-refresh ti-xs me-1"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Cari Layanan</label>
                                <input type="text" class="form-control" id="search"
                                    placeholder="Cari layanan premium...">
                            </div>
                            <div class="col-md-4">
                                <label for="service" class="form-label">Layanan</label>
                                <select class="form-control" id="service">
                                    <option value="">Semua Layanan</option>
                                    @foreach ($serviceNames as $service)
                                        <option value="{{ strtolower($service) }}">{{ $service }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="sort" class="form-label">Urutkan</label>
                                <select class="form-control" id="sort">
                                    <option value="price-asc">Harga Terendah</option>
                                    <option value="price-desc">Harga Tertinggi</option>
                                    <option value="name-asc">Nama A-Z</option>
                                    <option value="name-desc">Nama Z-A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Account Grid -->
        <div class="row" id="premium-grid">
            @forelse($data as $item)
                <div class="col-lg-4 col-md-6 premium-item mb-4" data-name="{{ strtolower($item->game) }}"
                    data-service="{{ strtolower($item->game) }}" data-price="{{ $item->price }}">
                    <div class="card h-100 premium-card shadow-sm">
                        <div class="card-header bg-gradient-primary py-3 text-center text-white">
                            {{-- Small icon, fallback to a generic icon if not found --}}
                            @php
                                $iconPath = asset("assets/img/products/{$item->game}.png");
                            @endphp
                            <img src="{{ $iconPath }}" alt="{{ $item->game }} logo" class="mb-2"
                                style="width:48px;height:48px;object-fit:contain;background:#fff;border-radius:8px;padding:4px;">
                            <h5 class="fw-bold mb-0 text-white">{{ $item->game }}</h5>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Service Info -->
                            <!-- Service Name -->
                            <p class="fw-bold text-dark py-3 text-center">{{ $item->name }}</p>

                            <!-- Price Section -->
                            <div class="mb-3">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div
                                            class="bg-light position-relative {{ Auth::user()->hasRole('member') ? 'border border-primary' : '' }} rounded p-2 text-center">
                                            @if (Auth::user()->hasRole('member'))
                                                <div class="position-absolute start-50 translate-middle top-0">
                                                    <span class="badge bg-primary">Level Anda</span>
                                                </div>
                                            @endif
                                            <small class="d-block mt-2">Member</small>
                                            <strong class="text-primary">Rp
                                                {{ number_format($item->price_member) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div
                                            class="bg-light position-relative {{ Auth::user()->hasRole('reseller') ? 'border border-success' : '' }} rounded p-2 text-center">
                                            @if (Auth::user()->hasRole('reseller'))
                                                <div class="position-absolute start-50 translate-middle top-0">
                                                    <span class="badge bg-success">Level Anda</span>
                                                </div>
                                            @endif
                                            <small class="d-block mt-2">Reseller</small>
                                            <strong class="text-success">Rp
                                                {{ number_format($item->price_reseller) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div
                                            class="bg-light position-relative {{ Auth::user()->hasRole('agen') ? 'border border-info' : '' }} rounded p-2 text-center">
                                            @if (Auth::user()->hasRole('agen'))
                                                <div class="position-absolute start-50 translate-middle top-0">
                                                    <span class="badge bg-info">Level Anda</span>
                                                </div>
                                            @endif
                                            <small class="d-block mt-2">Agen</small>
                                            <strong class="text-info">Rp {{ number_format($item->price_agen) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Info -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Kode:</small>
                                    <strong class="text-dark">{{ $item->code }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Server:</small>
                                    <strong class="text-dark">{{ $item->server ?? 'N/A' }}</strong>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                @if ($item->status == 1 || $item->status == true)
                                    <a href="{{ route('premium-order.show', $item->id) }}" class="btn btn-primary w-100">
                                        <i class="ti ti-shopping-cart ti-xs me-1"></i>Pesan Sekarang
                                    </a>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>
                                        <i class="ti ti-clock ti-xs me-1"></i>Tidak Tersedia
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="py-5 text-center">
                        <i class="ti ti-crown ti-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Belum Ada Layanan Premium</h4>
                        <p class="text-muted">Layanan premium akan segera tersedia</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- No Results Message -->
        <div id="no-results" class="py-5 text-center" style="display: none;">
            <i class="ti ti-search ti-4x text-muted mb-3"></i>
            <h4 class="text-muted">Tidak Ada Hasil</h4>
            <p class="text-muted">Coba kata kunci lain atau filter yang berbeda</p>
        </div>
    </div>
@endsection

@push('page-css')
    <style>
        .premium-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .service-icon {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .price-display {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }

        .service-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }

        .premium-item {
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            border-bottom: none;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }

        /* Role indicator styling */
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

@push('page-js')
    <script>
        // Search functionality
        document.getElementById('search').addEventListener('input', function() {
            filterItems();
        });

        // Service filter
        document.getElementById('service').addEventListener('change', function() {
            filterItems();
        });

        // Sort functionality
        document.getElementById('sort').addEventListener('change', function() {
            sortItems();
        });

        function syncData() {
            Swal.fire({
                title: 'Menyinkronkan Data...',
                text: 'Mohon tunggu sebentar, data sedang disinkronkan.',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                customClass: {
                    confirmButton: 'd-none'
                },
                buttonsStyling: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            $.ajax({
                url: '{{ route('premium-account.syncData') }}',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        Swal.close();
                        Swal.fire({
                            title: 'Success!',
                            text: 'Data fetched successfully.',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: {
                                confirmButton: 'd-none'
                            },
                            buttonsStyling: false,
                        })
                        setTimeout(() => {
                            refreshPage();
                        }, 1500);
                    } else {
                        Swal.close();
                        Swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    let message = "Error: " + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                        .message : error);
                    Swal.close();
                    Swal.fire({
                        title: 'Error',
                        text: message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                },
                complete: function() {
                    Swal.close();
                }
            });
        }

        function filterItems() {
            const searchTerm = document.getElementById('search').value.toLowerCase();
            const service = document.getElementById('service').value;
            const items = document.querySelectorAll('.premium-item');
            let visibleCount = 0;

            items.forEach(item => {
                const name = item.dataset.name;
                const itemService = item.dataset.service;

                const matchesSearch = name.includes(searchTerm);
                const matchesService = !service || itemService === service;

                if (matchesSearch && matchesService) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Show/hide no results message
            const noResults = document.getElementById('no-results');
            if (visibleCount === 0) {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }

        function sortItems() {
            const sortValue = document.getElementById('sort').value;
            const container = document.getElementById('premium-grid');
            const items = Array.from(container.querySelectorAll('.premium-item'));

            items.sort((a, b) => {
                switch (sortValue) {
                    case 'price-asc':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price-desc':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'name-asc':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    case 'name-desc':
                        return b.dataset.name.localeCompare(a.dataset.name);
                    default:
                        return 0;
                }
            });

            // Re-append sorted items
            items.forEach(item => container.appendChild(item));
        }


        function refreshPage() {
            location.reload();
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation delay to each item
            const items = document.querySelectorAll('.premium-item');
            items.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
@endpush
