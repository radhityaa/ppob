<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RechargeItemController;
use App\Http\Controllers\RechargeTitleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Setting\Landingpage\HeroController;
use App\Http\Controllers\TripayController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class);

Auth::routes();

Route::view('/banned', 'layouts.accountBanned')->name('account.banned');
Route::view('/suspend', 'layouts.accountSuspend')->name('account.suspend');

Route::middleware(['auth', 'checkuser'])->group(function () {
    // Roles & Permissions
    Route::get('get/roles', [RoleController::class, 'list'])->name('roles.list');
    Route::get('get/recharge-title', [RechargeTitleController::class, 'list'])->name('get.recharge-list');

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('home');

    // Deposit
    Route::resource('deposit', DepositController::class);
    Route::post('deposit-cancel/{deposit}', [DepositController::class, 'cancel'])->name('deposit.cancel');
    Route::post('deposit-confirm/{deposit}', [DepositController::class, 'confirm'])->name('deposit.confirm');

    // Payment Method
    Route::get('payment-method', [PaymentMethodController::class, 'index'])->name('payment-method.index');
    Route::post('payment-method', [PaymentMethodController::class, 'store'])->name('payment-method.store')->middleware('can: admin');
    Route::get('payment-method/{slug}/edit', [PaymentMethodController::class, 'show'])->name('payment-method.show');
    Route::put('payment-method/{slug}', [PaymentMethodController::class, 'update'])->name('payment-method.update')->middleware('can: admin');
    Route::delete('payment-method/{slug}', [PaymentMethodController::class, 'destroy'])->name('payment-method.destroy')->middleware('can: admin');

    // Get Payment Method Provider
    Route::get('payment-method-provider/{provider}', [PaymentMethodController::class, 'getPaymentProvider'])->name('payment-method.getPaymentProvider');
    Route::delete('payment-method-provider/{provider}', [PaymentMethodController::class, 'deletePaymentProvider'])->name('payment-method.deletePaymentProvider')->middleware('can: admin');

    // Get Payment Method
    Route::get('payment-method-list/{type}', [PaymentMethodController::class, 'list'])->name('payment-method.list');
    Route::get('payment-method-detail/{code}', [PaymentMethodController::class, 'detailMethod'])->name('payment-method.detailMethod');

    Route::prefix('admin')->group(function () {
        // Users
        Route::resource('users', UserController::class);

        // Recharge
        Route::prefix('recharge')->group(function () {
            Route::resource('title', RechargeTitleController::class);
            Route::resource('item', RechargeItemController::class);
        });
    });

    // Setting
    Route::prefix('settings')->group(function () {

        // ACL
        Route::prefix('acl')->group(function () {
            Route::resource('roles', RoleController::class);
            // Route::resource('navigation', NavigationController::class);
            Route::resource('permissions', PermissionController::class);
        });

        // Landing Page
        Route::prefix('landingpage')->group(function () {
            Route::resource('hero', HeroController::class);
        });
    });
});

Route::post('tripay/callback', [TripayController::class, 'callback'])->name('tripay.callback');
