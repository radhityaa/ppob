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
                <form id="formAccountSettings" method="GET" onsubmit="return false">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input class="form-control" type="text" id="name" name="name"
                                value="{{ old('name', $user->name) }}" autofocus />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input class="form-control" type="text" name="username" id="username"
                                value="{{ old('username', $user->username) }}" disabled />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <input class="form-control" type="text" id="email" name="email"
                                value="{{ old('email', $user->email) }}" placeholder="mail@example.com" />
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="phone" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $user->phone) }}" />
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-success me-2">Simpan</button>
                        <button type="reset" class="btn btn-label-secondary">Reset</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>

        <div class="card mb-4">
            <h5 class="card-header">Detail Toko</h5>
            <!-- Account -->
            <hr class="my-0" />
            <div class="card-body">
                <form id="formAccountSettings" method="GET" onsubmit="return false">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="shop_name" class="form-label">Nama Toko</label>
                            <input class="form-control" type="text" id="shop_name" name="shop_name"
                                value="{{ old('shop_name', $user->shop_name) }}" placeholder="Nama Toko" />
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="address" class="form-label">Alamat Toko</label>
                            <textarea name="address" id="address" class="form-control" placeholder="Alamat Lengkap Toko" required>{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-success me-2">Simpan</button>
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
