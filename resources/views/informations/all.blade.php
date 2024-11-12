@extends('layouts.administrator.app')

@push('page-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

    <style>
        .truncate-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Tentukan jumlah baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

@section('content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between flex-wrap gap-3">
            <div class="card-title mb-0 me-1">
                <h5 class="mb-1">Pusat Informasi</h5>
                <p class="text-muted mb-0">Informasi Terkini</p>
            </div>
            <div class="row col-lg-6">
                <form action="{{ route('information.all') }}" method="GET">
                    <div class="row">
                        <div class="col mb-2">
                            <select name="category" id="category" class="select2 form-select">
                                <option value="" selected disabled>--Pilih Kategori--</option>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}"
                                        {{ request('category') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col mb-2">
                            <select name="type" id="type" class="select2 form-select">
                                <option value="" selected disabled>--Pilih Tipe--</option>
                                <option value="Informasi" {{ request('type') == 'Informasi' ? 'selected' : '' }}>Informasi
                                </option>
                                <option value="Peringatan" {{ request('type') == 'Peringatan' ? 'selected' : '' }}>
                                    Peringatan
                                </option>
                                <option value="Penting" {{ request('type') == 'Penting' ? 'selected' : '' }}>Penting
                                </option>
                            </select>
                        </div>
                        <div class="col mb-2">
                            <div class="d-flex items-align-center">
                                <div>
                                    <button type="submit" class="btn btn-secondary me-2">Filter</button>
                                </div>
                                <div>
                                    <a href="{{ route('information.all') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row gy-4 mb-4">
                @foreach ($information as $item)
                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100 border p-2 shadow-none">
                            <div class="card-body p-3 pt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-secondary">{{ $item->categoryInformation->name }}</span>
                                    <span
                                        class="badge {{ $item->type === 'Informasi' ? 'bg-label-primary' : ($item->type === 'Peringatan' ? 'bg-label-warning' : 'bg-label-danger') }}">
                                        {{ $item->type }}
                                    </span>
                                </div>
                                <a href="{{ route('information.show', $item->slug) }}"
                                    class="h5">{{ $item->title }}</a>
                                <div style="font-size: 12px;">By <strong>{{ $item->user->name }}</strong></div>
                                <p class="truncate-2-lines mt-2">{!! $item->description !!}</p>
                                <p class="d-flex align-items-center">
                                    <i class="ti ti-clock mt-n1 me-2"></i>{{ $item->created_at->diffForHumans() }}
                                </p>
                                <a class="app-academy-md-50 btn btn-label-primary d-flex align-items-center"
                                    href="{{ route('information.show', $item->slug) }}">
                                    <span class="me-2">Selengkapnya</span>
                                    <i class="ti ti-chevron-right scaleX-n1-rtl ti-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        {{ $information->links('pagination::custom') }}
    </div>
@endsection

@push('page-js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#category').select2({
                placeholder: '-- Pilih Kategori --',
            });

            $('#type').select2({
                placeholder: '-- Pilih Tipe --',
            });
        })
    </script>
@endpush
