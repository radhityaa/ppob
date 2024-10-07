<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryInformationController;
use App\Http\Controllers\CekBillController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DownloadInvoiceController;
use App\Http\Controllers\EnvController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PrabayarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\RechargeItemController;
use App\Http\Controllers\RechargeTitleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Setting\Landingpage\HeroController;
use App\Http\Controllers\SettingMarginController;
use App\Http\Controllers\SettingPaymentGatewayController;
use App\Http\Controllers\SettingProviderController;
use App\Http\Controllers\SettingProviderProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\WhatsappGatewayController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class);

Auth::routes();

Route::view('/banned', 'layouts.accountBanned')->name('account.banned');
Route::view('/suspend', 'layouts.accountSuspend')->name('account.suspend');
Route::view('/comming-soon', 'errors.commingsoon')->name('commingsoon');

Route::middleware(['auth', 'checkuser'])->group(function () {
    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('{user}/account', [ProfileController::class, 'account'])->name('account');
        Route::get('{user}/shop', [ProfileController::class, 'shop'])->name('shop');
        Route::get('{user}/security', [ProfileController::class, 'security'])->name('security');

        Route::patch('{user}/account', [ProfileController::class, 'update'])->name('account.update');
    });

    // Roles & Permissions
    Route::get('get/roles', [RoleController::class, 'list'])->name('roles.list');
    Route::get('get/recharge-title', [RechargeTitleController::class, 'list'])->name('get.recharge-list');
    Route::get('users-list', [UserController::class, 'list'])->name('users.list');

    // Information
    Route::prefix('information')->name('information.')->group(function () {
        Route::post('update-information-user', [InformationController::class, 'updateInformationUser'])->name('update.user');
    });

    // Dashboard
    Route::get('/dashboard', DashboardController::class)->name('home');

    // Deposit
    Route::resource('deposit', DepositController::class);
    Route::post('deposit-cancel/{deposit}', [DepositController::class, 'cancel'])->name('deposit.cancel');
    Route::post('deposit-confirm/{deposit}', [DepositController::class, 'confirm'])->name('deposit.confirm');
    Route::post('deposit-print', [DepositController::class, 'print'])->name('deposit.print');

    // Transfer Saldo
    Route::prefix('transfer')->name('transfer.')->group(function () {
        Route::get('', [TransferController::class, 'index'])->name('index');
        Route::post('', [TransferController::class, 'store'])->name('store');
        Route::get('{transfer}', [TransferController::class, 'show'])->name('show');
    });

    // Payment Method
    Route::get('payment-method', [PaymentMethodController::class, 'index'])->name('payment-method.index');
    Route::post('payment-method', [PaymentMethodController::class, 'store'])->name('payment-method.store');
    Route::get('payment-method/{slug}/edit', [PaymentMethodController::class, 'show'])->name('payment-method.show');
    Route::put('payment-method/{slug}', [PaymentMethodController::class, 'update'])->name('payment-method.update');
    Route::delete('payment-method/{slug}', [PaymentMethodController::class, 'destroy'])->name('payment-method.destroy');

    // Get Payment Method Provider
    Route::get('payment-method-provider/{provider}', [PaymentMethodController::class, 'getPaymentProvider'])->name('payment-method.getPaymentProvider');
    Route::delete('payment-method-provider/{provider}', [PaymentMethodController::class, 'deletePaymentProvider'])->name('payment-method.deletePaymentProvider');

    // Get Payment Method
    Route::get('payment-method-list/{type}', [PaymentMethodController::class, 'list'])->name('payment-method.list');
    Route::get('payment-method-detail/{code}', [PaymentMethodController::class, 'detailMethod'])->name('payment-method.detailMethod');

    // Services
    Route::prefix('product')->group(function () {
        // Prabayar
        Route::prefix('prabayar')->group(function () {
            Route::get('', [PrabayarController::class, 'index'])->name('prabayar.index');
            Route::get('get-services-digiflazz', [PrabayarController::class, 'getServicesDigiflazz'])->name('prabayar.getServicesDigiflazz');
            Route::get('get-provider', [PrabayarController::class, 'getProvider'])->name('prabayar.getProvider');
            Route::get('get-type', [PrabayarController::class, 'getType'])->name('prabayar.getType');
            Route::get('get-services', [PrabayarController::class, 'getServices'])->name('prabayar.getServices');
            Route::get('get-services/{id}', [PrabayarController::class, 'detailServices'])->name('prabayar.detailServices');
            Route::delete('delete-services', [PrabayarController::class, 'deleteAllServices'])->name('prabayar.deleteServices');
            Route::get('{buyer_sku_code}', [PrabayarController::class, 'show'])->name('prabayar.show');
        });
    });

    // History Transaction
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('prabayar', [PrabayarController::class, 'history'])->name('prabayar');
        Route::post('prabayar/print', [PrabayarController::class, 'print'])->name('prabayar.print');
        Route::post('prabayar/wa', [PrabayarController::class, 'wa'])->name('prabayar.wa');
        Route::get('prabayar/{invoice}', [PrabayarController::class, 'historyDetail'])->name('prabayar.detail');
    });

    // Report
    Route::prefix('report')->name('report.')->group(function () {
        Route::get('transactions', [ReportController::class, 'transactions'])->name('transactions');
    });

    // Transaction
    Route::resource('transaction', TransactionController::class);

    // Admin Route
    Route::prefix('admin')->group(function () {
        // Users
        Route::resource('users', UserController::class);

        // Information
        Route::prefix('information')->name('information.')->group(function () {
            Route::get('', [InformationController::class, 'index'])->name('index');
            Route::get('create', [InformationController::class, 'create'])->name('create');
            Route::post('create', [InformationController::class, 'store'])->name('store');
            Route::get('{information}/show', [InformationController::class, 'show'])->name('show');
            Route::get('{information}/edit', [InformationController::class, 'edit'])->name('edit');
            Route::put('{information}/edit', [InformationController::class, 'update'])->name('update');
            Route::get('get-information', [InformationController::class, 'listInformation'])->name('list');
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

        Route::prefix('message-template')->name('message-template.')->group(function () {
            Route::get('', [MessageTemplateController::class, 'index'])->name('index');
            Route::get('{id}/edit', [MessageTemplateController::class, 'edit'])->name('edit');
            Route::put('{id}/edit', [MessageTemplateController::class, 'update'])->name('update');
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

        // Setting Margin
        Route::prefix('margin')->group(function () {
            Route::get('', [SettingMarginController::class, 'index'])->name('admin.setting.margin.index');
            Route::put('{id}', [SettingMarginController::class, 'update'])->name('admin.setting.margin.update');
        });

        // Env
        Route::prefix('env')->name('env.')->group(function () {
            Route::singleton('', EnvController::class);
        });

        // Provider
        Route::prefix('provider')->name('provider.')->group(function () {

            // Setting
            Route::get('setting', [SettingProviderController::class, 'setting'])->name('setting');
            Route::put('setting/{slug}', [SettingProviderController::class, 'update'])->name('update');
            Route::get('setting/{slug}', [SettingProviderController::class, 'edit'])->name('edit');

            Route::get('change', [SettingProviderController::class, 'change'])->name('change');
        });
    });

    // Get Category
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('kuota', [CategoryController::class, 'kuota'])->name('kuota');
        Route::get('get', [CategoryController::class, 'show'])->name('show');
    });

    // Check Bill
    Route::prefix('check')->name('check.')->group(function () {
        Route::post('token', [CekBillController::class, 'token'])->name('token');
    });

    // Orders Prabayar
    Route::prefix('prabayar')->name('prabayar.')->group(function () {
        Route::get('pulsa', [OrderController::class, 'pulsa'])->name('pulsa');
        Route::get('kuota', [OrderController::class, 'kuota'])->name('kuota');
        Route::get('token', [OrderController::class, 'token'])->name('token');

        // Emoney
        Route::prefix('emoney')->name('emoney.')->group(function () {
            Route::get('dana', [OrderController::class, 'dana'])->name('dana');
            Route::get('ovo', [OrderController::class, 'ovo'])->name('ovo');
            Route::get('grab', [OrderController::class, 'grab'])->name('grab');
            Route::get('gopay', [OrderController::class, 'gopay'])->name('gopay');
        });
    });

    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('', [WhatsappGatewayController::class, 'index'])->name('index');
        Route::post('{number}/update-status', [WhatsappGatewayController::class, 'updateStatus'])->name('updateStatus');
        Route::get('scan/{number}', [WhatsappGatewayController::class, 'scan'])->name('scan');
    });
});

Route::post('tripay/callback', [WebhookController::class, 'callbackTripay'])->name('tripay.callback');
Route::post('digiflazz/callback', [WebhookController::class, 'callbackDigiflazz'])->name('digiflazz.callback');
