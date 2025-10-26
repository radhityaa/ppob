@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Daftar Tiket Support</h4>
                        <a href="{{ route('ticket.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus ti-xs me-1"></i>Buat Tiket Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table-striped table">
                            <thead>
                                <tr>
                                    <th>No. Tiket</th>
                                    <th>Kategori</th>
                                    <th>Subjek</th>
                                    <th>Prioritas</th>
                                    <th>Status</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Ditugaskan Ke</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>
                                            <span class="fw-medium">{{ $ticket->ticket_number }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $ticket->category }}</span>
                                        </td>
                                        <td>{{ $ticket->subject }}</td>
                                        <td>
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
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>{{ $ticket->user->name }}</td>
                                        <td>
                                            @if ($ticket->assignedTo)
                                                {{ $ticket->assignedTo->name }}
                                            @else
                                                <span class="text-muted">Belum ditugaskan</span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('ticket.show', $ticket->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye ti-xs"></i>
                                                </a>
                                                @role('admin')
                                                    <a href="{{ route('ticket.edit', $ticket->id) }}"
                                                        class="btn btn-sm btn-outline-warning">
                                                        <i class="ti ti-edit ti-xs"></i>
                                                    </a>
                                                    <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini?')"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="ti ti-trash ti-xs"></i>
                                                        </button>
                                                    </form>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-4 text-center">
                                            <div class="text-muted">
                                                <i class="ti ti-ticket ti-3x mb-3"></i>
                                                <p class="mb-0">Belum ada tiket yang dibuat</p>
                                                <a href="{{ route('ticket.create') }}" class="btn btn-primary mt-2">
                                                    Buat Tiket Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($tickets->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
@endpush
