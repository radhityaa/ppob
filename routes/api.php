<?php

use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Server
Route::middleware(['VerifyApiKeyServer', 'CheckUser'])->group(function () {
    Route::prefix('/v1')->group(function () {

        // Users
        Route::prefix('users')->group(function () {
            // Route::post('check', [UserApiController::class, 'checkUser']);
            Route::post('saldo', [UserApiController::class, 'checkSaldo']);

            // Deposit
            Route::post('deposit', [UserApiController::class, 'deposit']);
            Route::post('deposit/channels', [UserApiController::class, 'depositChannels']);
            Route::post('deposit/detail', [UserApiController::class, 'depositDetail']);
            Route::post('deposit/list', [UserApiController::class, 'depositList']);
        });
    });
});
