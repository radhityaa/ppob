@extends('layouts.auth.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="#" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                fill="#7367F0" />
                            <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                            <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                                d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                fill="#7367F0" />
                        </svg>
                    </span>
                    <span class="app-brand-text demo text-body fw-bold ms-1">{{ config('app.name') }}</span>
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1 pt-2">Verifikasi OTP</h4>
            <p class="mb-4">Kode OTP Berhasil Dikirim Ke Whatsapp.</p>

            <form id="form-otp" class="mb-3" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="otp_code" class="form-label">Kode OTP</label>
                    <input type="number" class="form-control @error('otp_code') is-invalid @enderror" id="otp_code"
                        name="otp_code" value="{{ old('otp_code') }}" autocomplete="off" placeholder="xxxxxx" autofocus
                        required maxlength="6" pattern="\d{6}" title="Masukkan 6 digit angka"
                        oninput="this.value = this.value.slice(0, 6)" />

                    @error('otp_code')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <button class="btn btn-primary d-grid w-100 btn-submit" type="submit">Verifikasi</button>
                    <x-button-loading />
                </div>
            </form>

            <p id="countdown">Kirim ulang OTP dalam <span id="time">30</span> detik.</p>
            <form id="form-resend" method="POST">
                @csrf
                <button type="submit" id="resend-button" disabled class="btn btn-sm btn-success">Kirim Ulang OTP</button>
            </form>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        let timeLeft = 30;
        const countdownEl = document.getElementById('time');
        const countdownContainer = document.getElementById('countdown');
        const resendButton = document.getElementById('resend-button');

        $(document).ready(function() {
            startCountdown()

            let loadingButton = $('.btn-loading')
            let submitButton = $('.btn-submit')

            loadingButton.addClass('d-none')
            submitButton.removeClass('d-none')

            $('#form-otp').on('submit', function(e) {
                e.preventDefault();

                loadingButton.removeClass('d-none')
                submitButton.addClass('d-none')

                let formData = new FormData()
                formData.append('otp_code', $('#otp_code').val())

                $.ajax({
                    url: "{!! route('otp.verify') !!}",
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        loadingButton.addClass('d-none')
                        submitButton.removeClass('d-none')

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        })

                        setTimeout(() => {
                            window.location.href = '/dashboard';
                        }, 2000)
                    },
                    error: function(err) {
                        loadingButton.addClass('d-none')
                        submitButton.removeClass('d-none')

                        Swal.fire({
                            title: 'Gagal',
                            text: err.responseJSON.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        })
                    },
                    complete: function() {
                        loadingButton.addClass('d-none')
                        submitButton.removeClass('d-none')
                    }
                })
            })

            $('#form-resend').on('submit', function(e) {
                e.preventDefault();

                resendButton.disabled = true

                $.ajax({
                    url: "{!! route('otp.resend') !!}",
                    method: 'POST',
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        resendButton.disabled = false

                        startCountdown()

                        Swal.fire({
                            title: 'Berhasil',
                            text: res.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        })
                    },
                    error: function(err) {
                        resendButton.disabled = false

                        startCountdown()

                        $('.btn-loading-resend').addClass('d-none')
                        $('#resend-button').removeClass('d-none')

                        Swal.fire({
                            title: 'Gagal',
                            text: err.responseJSON.message,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500,
                            timerProgressBar: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        })
                    },
                })
            })
        })

        // Countdown timer function
        function startCountdown() {
            timeLeft = 30;
            countdownEl.textContent = timeLeft;
            countdownContainer.style.display = 'block';
            resendButton.disabled = true;

            const timer = setInterval(() => {
                timeLeft--;
                countdownEl.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    countdownContainer.style.display = 'none';
                    resendButton.disabled = false;
                }
            }, 1000);
        }
    </script>
@endpush
