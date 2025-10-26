@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Edit Tiket #{{ $ticket->ticket_number }}</h4>
                        <a href="{{ route('ticket.show', $ticket->id) }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left ti-xs me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori <span
                                            class="text-danger">*</span></label>
                                    <select name="category" id="category"
                                        class="form-select @error('category') is-invalid @enderror" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}"
                                                {{ old('category', $ticket->category) == $category ? 'selected' : '' }}>
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
                                        @foreach ($priorities as $priority)
                                            <option value="{{ $priority }}"
                                                {{ old('priority', $ticket->priority) == $priority ? 'selected' : '' }}>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select name="status" id="status"
                                        class="form-select @error('status') is-invalid @enderror" required>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="assigned_to" class="form-label">Ditugaskan Ke</label>
                                    <select name="assigned_to" id="assigned_to"
                                        class="form-select @error('assigned_to') is-invalid @enderror">
                                        <option value="">Pilih Admin</option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}"
                                                {{ old('assigned_to', $ticket->assigned_to) == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject"
                                class="form-control @error('subject') is-invalid @enderror"
                                value="{{ old('subject', $ticket->subject) }}" placeholder="Masukkan subjek tiket"
                                required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="6" placeholder="Jelaskan masalah atau pertanyaan Anda secara detail" required>{{ old('description', $ticket->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('ticket.show', $ticket->id) }}" class="btn btn-outline-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check ti-xs me-1"></i>Update Tiket
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
