@extends('layouts.administrator.app')

@section('content')
    <div class="card">
        <div class="card-body row g-3">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                    <div class="me-1">
                        <h4 class="mb-1">{{ $information->title }}</h4>
                        <p class="mb-1" style="font-size: 13px;">{{ $information->created_at->diffForHumans() }}</p>
                        <span class="badge bg-secondary rounded-pill"
                            style="font-size: 10px;">{{ $information->categoryInformation->name }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span
                            class="badge {{ $information->type === 'Informasi' ? 'bg-info' : ($information->type === 'Peringatan' ? 'bg-warning' : 'bg-danger') }}">{{ $information->type }}</span>
                    </div>
                </div>
                <div class="card academy-content border shadow-none">
                    <div class="card-body">
                        {!! $information->description !!}
                        <hr class="my-4" />
                        <h5>Dibuat Oleh</h5>
                        <div class="d-flex justify-content-start align-items-center user-name">
                            <div class="avatar-wrapper">
                                <div class="avatar me-2">
                                    <img src="{{ asset('assets/img/avatars/11.png') }}" alt="Avatar"
                                        class="rounded-circle" />
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-medium">{{ $information->user->name }}</span>
                                <small class="text-muted">{{ Auth::user()->getRoleNames()[0] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
