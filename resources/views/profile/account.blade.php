@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <x-profile-layout username="{{ $user->username }}">
        <div class="card mb-4">
            <h5 class="card-header">Detail Profile</h5>
            <!-- Account -->
            <hr class="my-0" />
            <div class="card-body">
                <form action="{{ route('profile.account.update', $user->username) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name"
                                name="name" value="{{ old('name', $user->name) }}" autofocus required />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input class="form-control @error('username') is-invalid @enderror" type="text"
                                name="username" id="username" value="{{ old('username', $user->username) }}" disabled />
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control @error('email') is-invalid @enderror" type="text" id="email"
                                name="email" value="{{ old('email', $user->email) }}" placeholder="mail@example.com"
                                required />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">No. HP</label>
                            <input type="number" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone', $user->phone) }}" required />
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-success me-2">Update</button>
                        <button type="reset" class="btn btn-label-secondary">Reset</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </x-profile-layout>
@endsection

@push('page-js')
@endpush
