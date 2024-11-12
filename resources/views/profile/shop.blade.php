@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <x-profile-layout username="{{ $user->username }}">
        <div class="card mb-4">
            <h5 class="card-header">Detail Toko</h5>
            <!-- Account -->
            <hr class="my-0" />
            <div class="card-body">
                <form action="{{ route('profile.account.update', $user->username) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="shop_name" class="form-label">Nama Toko</label>
                            <input class="form-control @error('shop_name') is-invalid @enderror" type="text"
                                id="shop_name" name="shop_name" value="{{ old('shop_name', $user->shop_name) }}"
                                placeholder="Nama Toko" />
                            @error('shop_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="address" class="form-label">Alamat Toko</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                placeholder="Alamat Lengkap Toko" required>{{ old('address', $user->address) }}</textarea>
                            @error('address')
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
