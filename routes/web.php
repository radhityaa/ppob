<?php

use App\Http\Controllers\ForgotController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class);

Auth::routes();

Route::view('/banned', 'layouts.accountBanned')->name('account.banned');
Route::view('/suspend', 'layouts.accountSuspend')->name('account.suspend');
Route::view('/comming-soon', 'errors.commingsoon')->name('commingsoon');

Route::middleware('guest')->group(function () {
    Route::prefix('otp')->name('otp.')->group(function () {
        Route::get('verification', [OtpController::class, 'showOtpForm'])->name('verify.show')->middleware('checkOtp');
        Route::post('send', [OtpController::class, 'sendOtp'])->name('send');
        Route::post('verify', [OtpController::class, 'verifyOtp'])->name('verify');
        Route::post('resend', [OtpController::class, 'resendOtp'])->name('resend');
    });

    Route::get('reset-password', [ForgotController::class, 'index'])->name('reset.index');
    Route::post('reset-password', [ForgotController::class, 'sendVerificationCode']);
    Route::get('reset-password/{phone}/{token}', [ForgotController::class, 'verify'])->name('reset.verify');
    Route::post('reset-password/{phone}/{token}', [ForgotController::class, 'changePassword']);
});

Route::middleware(['auth', 'checkuser'])->group(function () {
    // Admin Route
    require __DIR__ . '/admin.php';

    // User Route
    require __DIR__ . '/user.php';
});

// Callback Route
require __DIR__ . '/callback.php';
