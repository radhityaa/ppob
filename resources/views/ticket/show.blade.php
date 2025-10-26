@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Detail Tiket #{{ $ticket->ticket_number }}</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('ticket.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left ti-xs me-1"></i>Kembali
                            </a>
                            @role('admin')
                                <a href="{{ route('ticket.edit', $ticket->id) }}" class="btn btn-warning">
                                    <i class="ti ti-edit ti-xs me-1"></i>Edit
                                </a>
                            @endrole
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Informasi Tiket</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>No. Tiket:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <span class="badge bg-primary fs-6">{{ $ticket->ticket_number }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Kategori:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <span class="badge bg-info">{{ $ticket->category }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Subjek:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $ticket->subject }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Prioritas:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            @php
                                                $priorityColors = [
                                                    'Low' => 'bg-success',
                                                    'Medium' => 'bg-warning',
                                                    'High' => 'bg-danger',
                                                    'Urgent' => 'bg-dark',
                                                ];
                                            @endphp
                                            <span class="badge {{ $priorityColors[$ticket->priority] }}">
                                                {{ $ticket->priority }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Status:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            @php
                                                $statusColors = [
                                                    'Open' => 'bg-primary',
                                                    'In Progress' => 'bg-warning',
                                                    'Resolved' => 'bg-success',
                                                    'Closed' => 'bg-secondary',
                                                ];
                                            @endphp
                                            <span class="badge {{ $statusColors[$ticket->status] }}">
                                                {{ $ticket->status }}
                                            </span>

                                            {{-- Admin Status Control --}}
                                            @if (Auth::user()->hasRole('admin'))
                                                <div class="mt-2">
                                                    <form action="{{ route('ticket.update-status', $ticket->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')

                                                        <div class="d-flex align-items-center gap-2">
                                                            <select name="status" class="form-select form-select-sm"
                                                                style="width: auto;" onchange="this.form.submit()">
                                                                <option value="Open"
                                                                    {{ $ticket->status === 'Open' ? 'selected' : '' }}>Open
                                                                </option>
                                                                <option value="In Progress"
                                                                    {{ $ticket->status === 'In Progress' ? 'selected' : '' }}>
                                                                    In Progress</option>
                                                                <option value="Resolved"
                                                                    {{ $ticket->status === 'Resolved' ? 'selected' : '' }}>
                                                                    Resolved</option>
                                                                <option value="Closed"
                                                                    {{ $ticket->status === 'Closed' ? 'selected' : '' }}>
                                                                    Closed</option>
                                                            </select>
                                                            <small class="text-muted">
                                                                <i class="ti ti-info-circle ti-xs me-1"></i>
                                                                Klik untuk mengubah status
                                                            </small>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-3">
                                            <strong>Deskripsi:</strong>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="bg-light rounded p-3">
                                                {!! nl2br(e($ticket->description)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Informasi Tambahan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Dibuat Oleh:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $ticket->user->name }}
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Ditugaskan Ke:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            @if ($ticket->assignedTo)
                                                {{ $ticket->assignedTo->name }}
                                            @else
                                                <span class="text-muted">Belum ditugaskan</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Admin Tersedia:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($admins as $admin)
                                                    <span class="badge bg-info">{{ $admin->name }}</span>
                                                @endforeach
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                <i class="ti ti-info-circle ti-xs me-1"></i>
                                                Semua admin dapat membalas tiket ini
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Tanggal Dibuat:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $ticket->created_at->format('d M Y H:i') }}
                                        </div>
                                    </div>

                                    @if ($ticket->resolved_at)
                                        <div class="row mb-3">
                                            <div class="col-sm-4">
                                                <strong>Tanggal Selesai:</strong>
                                            </div>
                                            <div class="col-sm-8">
                                                {{ $ticket->resolved_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Terakhir Diupdate:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            {{ $ticket->updated_at->format('d M Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($ticket->status === 'Resolved' || $ticket->status === 'Closed')
                                <div class="card mt-3">
                                    <div class="card-body text-center">
                                        <i class="ti ti-check-circle ti-3x text-success mb-3"></i>
                                        <h5 class="text-success">Tiket Telah Selesai</h5>
                                        <p class="text-muted mb-0">
                                            Tiket ini telah diselesaikan pada
                                            {{ $ticket->resolved_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Chat/Balasan Tiket --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <i class="ti ti-messages ti-xs me-2"></i>Percakapan Tiket
                                        </h5>
                                        @if (Auth::user()->hasRole('admin'))
                                            <span class="badge bg-success">
                                                <i class="ti ti-shield-check ti-xs me-1"></i>Admin - Dapat Membalas
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    {{-- Daftar Balasan --}}
                                    <div class="chat-container position-relative"
                                        style="max-height: 500px; overflow-y: auto; min-height: 400px;">
                                        {{-- Scroll Indicator --}}
                                        <div id="scrollIndicator"
                                            class="position-absolute start-50 translate-middle-x bg-primary rounded-pill top-0 px-3 py-1 text-white"
                                            style="font-size: 0.75rem; z-index: 10; display: none;">
                                            <i class="ti ti-arrow-up ti-xs me-1"></i>Scroll ke atas untuk melihat lebih
                                            banyak
                                        </div>
                                        @forelse($ticket->replies as $reply)
                                            <div
                                                class="d-flex {{ $reply->is_admin ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                                                <div class="card chat-bubble {{ $reply->is_admin ? 'bg-primary text-white' : 'bg-light' }}"
                                                    style="max-width: 60%;">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <strong class="small" style="font-size: 0.75rem;">
                                                                {{ $reply->user->name }}
                                                                @if ($reply->is_admin)
                                                                    <span class="badge bg-warning text-dark ms-1"
                                                                        style="font-size: 0.6rem;">Admin</span>
                                                                @endif
                                                            </strong>
                                                            <small class="opacity-75" style="font-size: 0.7rem;">
                                                                {{ $reply->created_at->format('d M H:i') }}
                                                            </small>
                                                        </div>
                                                        <p class="mb-0" style="font-size: 0.85rem;">
                                                            {{ $reply->message }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="py-3 text-center">
                                                <i class="ti ti-messages ti-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0" style="font-size: 0.85rem;">Belum ada balasan
                                                </p>
                                            </div>
                                        @endforelse

                                        {{-- Tampilkan Gambar dalam Chat --}}
                                        @if ($ticket->attachments->count() > 0)
                                            @foreach ($ticket->attachments as $attachment)
                                                <div
                                                    class="d-flex {{ $attachment->user->hasRole('admin') ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                                                    <div class="card chat-bubble {{ $attachment->user->hasRole('admin') ? 'bg-primary text-white' : 'bg-light' }}"
                                                        style="max-width: 60%;">
                                                        <div class="card-body p-2">
                                                            <div
                                                                class="d-flex justify-content-between align-items-start mb-1">
                                                                <strong class="small" style="font-size: 0.75rem;">
                                                                    {{ $attachment->user->name }}
                                                                    @if ($attachment->user->hasRole('admin'))
                                                                        <span class="badge bg-warning text-dark ms-1"
                                                                            style="font-size: 0.6rem;">Admin</span>
                                                                    @endif
                                                                </strong>
                                                                <small class="opacity-75" style="font-size: 0.7rem;">
                                                                    {{ $attachment->created_at->format('d M H:i') }}
                                                                </small>
                                                            </div>
                                                            <div class="position-relative">
                                                                <img src="{{ $attachment->url }}"
                                                                    class="chat-image rounded"
                                                                    style="width: 150px; height: 100px; object-fit: cover; cursor: pointer;"
                                                                    alt="{{ $attachment->original_name }}"
                                                                    onclick="openImageModal('{{ $attachment->url }}', '{{ $attachment->original_name }}')">
                                                                <div class="position-absolute end-0 top-0 p-1">
                                                                    <form
                                                                        action="{{ route('ticket.delete-image', [$ticket->id, $attachment->id]) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus gambar ini?')"
                                                                        class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-danger"
                                                                            style="font-size: 0.6rem; padding: 0.2rem 0.4rem;">
                                                                            <i class="ti ti-trash ti-xs"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <small class="text-muted d-block mt-1"
                                                                style="font-size: 0.65rem;">
                                                                {{ Str::limit($attachment->original_name, 20) }}
                                                                ({{ $attachment->formatted_size }})
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    {{-- Form Balasan --}}
                                    @if ($ticket->status !== 'Closed')
                                        <hr>

                                        {{-- Notifikasi Sukses --}}
                                        @if (session('success'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <i class="ti ti-check-circle ti-xs me-2"></i>
                                                <strong>Berhasil!</strong> {{ session('success') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        @endif

                                        {{-- Notifikasi Error --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <i class="ti ti-alert-circle ti-xs me-2"></i>
                                                <strong>Terjadi kesalahan:</strong>
                                                <ul class="mb-0 mt-2">
                                                    @foreach ($errors->all() as $error)
                                                        <li style="font-size: 0.85rem;">{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        @endif

                                        <form action="{{ route('ticket.reply', $ticket->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-2">
                                                <label for="message" class="form-label" style="font-size: 0.85rem;">
                                                    @if (Auth::user()->hasRole('admin'))
                                                        <i class="ti ti-user-check ti-xs me-1"></i>Balasan Admin
                                                        ({{ Auth::user()->name }})
                                                    @else
                                                        <i class="ti ti-user ti-xs me-1"></i>Balasan Anda
                                                    @endif
                                                </label>
                                                <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="2"
                                                    placeholder="Ketik balasan Anda di sini..." required style="font-size: 0.85rem;">{{ old('message') }}</textarea>
                                                @error('message')
                                                    <div class="invalid-feedback" style="font-size: 0.75rem;">
                                                        {{ $message }}</div>
                                                @enderror
                                                <div class="form-text" style="font-size: 0.75rem;">
                                                    <i class="ti ti-info-circle ti-xs me-1"></i>
                                                    <span id="charCount">0</span>/5000 karakter
                                                </div>
                                            </div>

                                            {{-- Form Upload Gambar --}}
                                            <div class="mb-2">
                                                <label for="images" class="form-label" style="font-size: 0.85rem;">
                                                    <i class="ti ti-photo ti-xs me-1"></i>Upload Gambar (Opsional)
                                                </label>
                                                <input type="file" name="images[]" id="images"
                                                    class="form-control @error('images.*') is-invalid @enderror" multiple
                                                    accept="image/*" style="font-size: 0.85rem;">
                                                @error('images.*')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text" style="font-size: 0.75rem;">
                                                    <i class="ti ti-info-circle ti-xs me-1"></i>
                                                    Format: JPEG, JPG, PNG, GIF, WEBP, BMP. Maksimal 5MB per file.
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                @if (Auth::user()->hasRole('admin'))
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        <i class="ti ti-info-circle ti-xs me-1"></i>
                                                        Sebagai admin, Anda dapat membalas tiket ini
                                                    </small>
                                                @else
                                                    <small class="text-muted" style="font-size: 0.75rem;">
                                                        <i class="ti ti-info-circle ti-xs me-1"></i>
                                                        Balasan Anda akan dilihat oleh semua admin
                                                    </small>
                                                @endif
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="ti ti-send ti-xs me-1"></i>Kirim
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-info text-center">
                                            <i class="ti ti-lock ti-xs me-1"></i>
                                            Tiket ini telah ditutup dan tidak dapat dibalas lagi.
                                        </div>
                                    @endif
                                </div>
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
        .chat-container {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            background-color: #f8f9fa;
        }

        .chat-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .chat-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .chat-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .chat-bubble {
            transition: all 0.3s ease;
        }

        .chat-bubble:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .chat-image {
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .chat-image:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .chat-container {
                max-height: 400px;
                min-height: 300px;
            }

            .chat-bubble {
                max-width: 85% !important;
            }
        }
    </style>
@endpush

@push('page-js')
    <script>
        function openImageModal(imageUrl, imageName) {
            // Buat modal dinamis
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = 'imageModal';
            modal.innerHTML = `
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${imageName}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="${imageUrl}" class="img-fluid" alt="${imageName}" style="max-height: 70vh;">
                    </div>
                </div>
            </div>
        `;

            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            // Hapus modal dari DOM setelah ditutup
            modal.addEventListener('hidden.bs.modal', function() {
                document.body.removeChild(modal);
            });
        }

        // Validasi file upload
        document.getElementById('images').addEventListener('change', function(e) {
            const files = e.target.files;
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
            const errors = [];

            // Hapus notifikasi error sebelumnya
            const existingAlert = document.querySelector('.alert-danger');
            if (existingAlert) {
                existingAlert.remove();
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Validasi ukuran file
                if (file.size > maxSize) {
                    errors.push(`${file.name}: Ukuran file terlalu besar (maksimal 5MB)`);
                }

                // Validasi tipe file
                if (!allowedTypes.includes(file.type)) {
                    errors.push(`${file.name}: Format file tidak diizinkan`);
                }

                // Validasi nama file
                if (file.name.includes('..') || file.name.includes('/')) {
                    errors.push(`${file.name}: Nama file tidak valid`);
                }
            }

            // Tampilkan error jika ada
            if (errors.length > 0) {
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger alert-dismissible fade show';
                errorAlert.innerHTML = `
                    <i class="ti ti-alert-circle ti-xs me-2"></i>
                    <strong>Validasi File Gagal:</strong>
                    <ul class="mb-0 mt-2">
                        ${errors.map(error => `<li style="font-size: 0.85rem;">${error}</li>`).join('')}
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                // Sisipkan sebelum form
                const form = document.querySelector('form');
                form.parentNode.insertBefore(errorAlert, form);

                // Clear file input
                e.target.value = '';
            }
        });

        // Character counter untuk textarea
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('charCount');

        if (messageTextarea && charCount) {
            messageTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;

                // Ubah warna jika mendekati limit
                if (length > 4500) {
                    charCount.style.color = '#dc3545';
                } else if (length > 4000) {
                    charCount.style.color = '#fd7e14';
                } else {
                    charCount.style.color = '#6c757d';
                }
            });

            // Set initial count
            charCount.textContent = messageTextarea.value.length;
        }

        // Validasi form sebelum submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const message = document.getElementById('message').value.trim();
            const files = document.getElementById('images').files;

            // Validasi pesan kosong
            if (message.length === 0) {
                e.preventDefault();
                showError('Pesan tidak boleh kosong');
                return;
            }

            // Validasi panjang pesan
            if (message.length > 5000) {
                e.preventDefault();
                showError('Pesan terlalu panjang (maksimal 5000 karakter)');
                return;
            }

            // Validasi file jika ada
            if (files.length > 0) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
                    'image/bmp'
                ];

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    if (file.size > maxSize) {
                        e.preventDefault();
                        showError(`${file.name}: Ukuran file terlalu besar (maksimal 5MB)`);
                        return;
                    }

                    if (!allowedTypes.includes(file.type)) {
                        e.preventDefault();
                        showError(`${file.name}: Format file tidak diizinkan`);
                        return;
                    }
                }
            }
        });

        // Fungsi untuk menampilkan error
        function showError(message) {
            // Hapus alert error sebelumnya
            const existingAlert = document.querySelector('.alert-danger');
            if (existingAlert) {
                existingAlert.remove();
            }

            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show';
            errorAlert.innerHTML = `
                <i class="ti ti-alert-circle ti-xs me-2"></i>
                <strong>Validasi Gagal:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Sisipkan sebelum form
            const form = document.querySelector('form');
            form.parentNode.insertBefore(errorAlert, form);
        }

        // Auto-scroll ke bawah chat
        function scrollToBottom() {
            const chatContainer = document.querySelector('.chat-container');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }

        // Kontrol scroll indicator
        function updateScrollIndicator() {
            const chatContainer = document.querySelector('.chat-container');
            const scrollIndicator = document.getElementById('scrollIndicator');

            if (chatContainer && scrollIndicator) {
                const scrollTop = chatContainer.scrollTop;
                const scrollHeight = chatContainer.scrollHeight;
                const clientHeight = chatContainer.clientHeight;

                // Tampilkan indicator jika ada konten di atas
                if (scrollTop > 50) {
                    scrollIndicator.style.display = 'block';
                } else {
                    scrollIndicator.style.display = 'none';
                }
            }
        }

        // Scroll ke bawah saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            scrollToBottom();

            // Tambahkan event listener untuk scroll
            const chatContainer = document.querySelector('.chat-container');
            if (chatContainer) {
                chatContainer.addEventListener('scroll', updateScrollIndicator);
            }
        });

        // Auto-scroll saat ada perubahan di chat
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    scrollToBottom();
                }
            });
        });

        const chatContainer = document.querySelector('.chat-container');
        if (chatContainer) {
            observer.observe(chatContainer, {
                childList: true,
                subtree: true
            });
        }

        // Auto-hide alerts setelah 5 detik
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-success')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>
@endpush
