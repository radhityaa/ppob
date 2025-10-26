<?php

use App\Http\Controllers\Admin\DigiflazzSyncController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CategoryInformationController;
use App\Http\Controllers\EnvController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\PremiumAccountController;
use App\Http\Controllers\RechargeItemController;
use App\Http\Controllers\RechargeTitleController;
use App\Http\Controllers\Setting\Landingpage\HeroController;
use App\Http\Controllers\SettingMarginController;
use App\Http\Controllers\SettingProfitController;
use App\Http\Controllers\SettingProviderController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhatsappGatewayController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Admin Dashboard
    Route::prefix('dashboard')->name('admin.')->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('data', [DashboardController::class, 'getData'])->name('data');
    });

    // Admin Report
    Route::prefix('report')->name('admin.report.')->group(function () {
        Route::get('', [ReportController::class, 'index'])->name('index');
        Route::get('export', [ReportController::class, 'export'])->name('export');
    });

    // Digiflazz Sync
    Route::prefix('digiflazz-sync')->name('admin.digiflazz-sync.')->group(function () {
        Route::get('/', [DigiflazzSyncController::class, 'index'])->name('index');
        Route::post('/sync-all', [DigiflazzSyncController::class, 'syncAll'])->name('sync-all');
        Route::post('/sync-category', [DigiflazzSyncController::class, 'syncCategory'])->name('sync-category');
        Route::get('/stats', [DigiflazzSyncController::class, 'stats'])->name('stats');
        Route::post('/clear-cache', [DigiflazzSyncController::class, 'clearCache'])->name('clear-cache');
    });

    // Users
    Route::resource('users', UserController::class);

    // Landing Page
    Route::prefix('landingpage/settings')->group(function () {
        Route::resource('hero', HeroController::class);
    });

    // Setting
    Route::prefix('settings')->name('admin.settings.')->group(function () {
        Route::get('', [SettingsController::class, 'index'])->name('index');

        // Setting Margin
        Route::prefix('margin')->group(function () {
            Route::get('', [SettingMarginController::class, 'index'])->name('margin.index');
            Route::put('', [SettingMarginController::class, 'update'])->name('margin.update');
        });

        // Profit Reseller
        Route::prefix('profit')->group(function () {
            Route::get('', [SettingProfitController::class, 'index'])->name('profit.index');
            Route::put('', [SettingProfitController::class, 'update'])->name('profit.update');
        });

        // Notification
        Route::get('notification', [SettingsController::class, 'notification'])->name('notification');
        Route::put('notification', [SettingsController::class, 'notificationUpdate'])->name('notification.update');

        // Message Template
        Route::prefix('message-template')->name('message-template.')->group(function () {
            Route::get('', [MessageTemplateController::class, 'index'])->name('template.index');
            Route::get('{id}/edit', [MessageTemplateController::class, 'edit'])->name('template.edit');
            Route::put('{id}/edit', [MessageTemplateController::class, 'update'])->name('template.update');
        });

        // Provider
        Route::prefix('provider')->name('provider.')->group(function () {
            // Setting
            Route::get('setting', [SettingProviderController::class, 'setting'])->name('setting');
            Route::put('setting/{slug}', [SettingProviderController::class, 'update'])->name('update');
            Route::get('setting/{slug}', [SettingProviderController::class, 'edit'])->name('edit');

            Route::get('change', [SettingProviderController::class, 'change'])->name('change');
        });

        // Information Deposit
        Route::get('information-deposit', [SettingsController::class, 'informationDeposit'])->name('informationDeposit');
        Route::put('information-deposit', [SettingsController::class, 'informationDepositUpdate']);

        // Env
        Route::prefix('env')->name('env.')->group(function () {
            Route::singleton('', EnvController::class);
        });

        Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
            Route::post('update-status', [WhatsappGatewayController::class, 'updateStatus'])->name('updateStatus');
            Route::post('add-device', [WhatsappGatewayController::class, 'store'])->name('store');
            Route::delete('delete-device', [WhatsappGatewayController::class, 'destroy'])->name('destroy');
        });
    });

    // Information
    Route::prefix('information')->name('information.')->group(function () {
        Route::get('', [InformationController::class, 'index'])->name('index');
        Route::get('create', [InformationController::class, 'create'])->name('create');
        Route::post('create', [InformationController::class, 'store'])->name('store');
        Route::get('{information}/edit', [InformationController::class, 'edit'])->name('edit');
        Route::put('{information}/edit', [InformationController::class, 'update'])->name('update');
        Route::get('get-information', [InformationController::class, 'listInformation'])->name('list');
        Route::get('get-information/{categoryInformation}', [InformationController::class, 'listInformationByCategory'])->name('listByCategory');
        Route::delete('{information}', [InformationController::class, 'destroy'])->name('destroy');

        // Category
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('', [CategoryInformationController::class, 'index'])->name('index');
            Route::post('', [CategoryInformationController::class, 'store'])->name('store');
            Route::get('{categoryInformation}', [CategoryInformationController::class, 'edit'])->name('edit');
            Route::put('{categoryInformation}', [CategoryInformationController::class, 'update'])->name('update');
            Route::delete('{categoryInformation}', [CategoryInformationController::class, 'destroy'])->name('destroy');
        });
    });

    // Recharge
    Route::prefix('recharge')->group(function () {
        Route::resource('title', RechargeTitleController::class);
        Route::resource('item', RechargeItemController::class);
    });

    // Premium Account
    Route::prefix('premium-account')->name('premium-account.')->group(function () {
        Route::get('sync-data', [PremiumAccountController::class, 'syncData'])->name('syncData');
    });
});
