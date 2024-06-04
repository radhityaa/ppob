<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RechargeItemController;
use App\Http\Controllers\RechargeTitleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Setting\Landingpage\HeroController;
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
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // konfigurasi
    Route::resource('konfigurasi/roles', RoleController::class);
    Route::resource('konfigurasi/navigation', NavigationController::class);
    Route::resource('konfigurasi/permissions', PermissionController::class);

    // Users
    Route::resource('admin/users', UserController::class);

    // Setting
    Route::prefix('settings')->group(function () {

        // Landing Page
        Route::prefix('landingpage')->group(function () {
            Route::resource('hero', HeroController::class);
        });
    });

    // Recharge
    Route::prefix('recharge')->group(function () {
        Route::resource('title', RechargeTitleController::class);
        Route::resource('item', RechargeItemController::class);
    });
});
