@extends('layouts.administrator.app')

@push('page-css')
@endpush

@section('content')
    <x-profile-layout username="{{ $user->username }}">
        <div class="row">
            <!-- Change Password -->
            <div class="card mb-4">
                <h5 class="card-header">Ubah Password</h5>
                <div class="card-body">
                    <form action="{{ route('profile.account.update', $user->username) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="currentPassword">Password Lama</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control @error('currentPassword') is-invalid @enderror" required
                                        type="password" name="currentPassword" id="currentPassword"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    @error('currentPassword')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password">Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                        required id="password" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 col-md-6 form-password-toggle">
                                <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="password_confirmation" required
                                        id="password_confirmation"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <h6>Password Requirements:</h6>
                                <ul class="ps-3 mb-0">
                                    <li class="mb-1">Panjang minimal 8 karakter - semakin banyak, semakin baik</li>
                                    <li class="mb-1">Setidaknya satu karakter huruf besar</li>
                                    <li>Setidaknya ada satu angka, simbol, atau karakter</li>
                                </ul>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success me-2">Update</button>
                                <button type="reset" class="btn btn-label-secondary">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--/ Change Password -->
        </div>
    </x-profile-layout>
@endsection

@push('page-js')
@endpush
