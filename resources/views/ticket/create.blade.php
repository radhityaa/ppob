@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Buat Tiket Support Baru</h4>
                        <a href="{{ route('ticket.index') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left ti-xs me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ticket.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select name="category" id="category"
                                        class="form-select @error('category') is-invalid @enderror" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ old('category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Prioritas <span
                                            class="text-danger">*</span></label>
                                    <select name="priority" id="priority"
                                        class="form-select @error('priority') is-invalid @enderror" required>
                                        <option value="">Pilih Prioritas</option>
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority }}"
                                                {{ old('priority') == $priority ? 'selected' : '' }}>
                                                {{ $priority }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject"
                                class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}"
                                placeholder="Masukkan subjek tiket" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="6" placeholder="Jelaskan masalah atau pertanyaan Anda secara detail" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Jelaskan masalah atau pertanyaan Anda dengan detail agar tim support dapat membantu dengan
                                lebih baik.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('ticket.index') }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-send ti-xs me-1"></i>Kirim Tiket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
@endpush
