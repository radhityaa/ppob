<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DigiflazzSyncController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CekBillController;
use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RechargeTitleController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\MutationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPascaController;
use App\Http\Controllers\PascabayarController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PrabayarController;
use App\Http\Controllers\PremiumAccountController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RegisterAgenController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionGameFeatureController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithDrawalController;

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
    Route::get('', [InformationController::class, 'all'])->name('all');
    Route::post('update-information-user', [InformationController::class, 'updateInformationUser'])->name('update.user');
    Route::get('{information}/show', [InformationController::class, 'show'])->name('show');
});

// Dashboard
Route::get('/dashboard', DashboardController::class)->name('home');

// Deposit
Route::resource('deposit', DepositController::class);
Route::post('deposit-cancel/{deposit}', [DepositController::class, 'cancel'])->name('deposit.cancel');
Route::post('deposit-confirm/{deposit}', [DepositController::class, 'confirm'])->name('deposit.confirm');
Route::post('deposit-print', [DepositController::class, 'print'])->name('deposit.print');

// Mutation
Route::prefix('mutations')->name('mutations.')->group(function () {
    Route::get('', [MutationController::class, 'index'])->name('index');
});

Route::prefix('profits')->name('profits.')->group(function () {
    Route::get('history', [ProfitController::class, 'index'])->name('index');

    Route::prefix('withdrawal')->name('withdrawal.')->group(function () {
        Route::get('', [WithDrawalController::class, 'index'])->name('index');
        Route::post('', [WithDrawalController::class, 'store'])->name('store');
    });
});

// Transfer Saldo
Route::prefix('transfer')->name('transfer.')->group(function () {
    Route::get('', [TransferController::class, 'index'])->name('index');
    Route::post('', [TransferController::class, 'store'])->name('store');
    Route::get('{transfer}', [TransferController::class, 'show'])->name('show');
});

// Ticket Support
Route::resource('ticket', TicketController::class);
Route::post('ticket/{ticket}/reply', [TicketController::class, 'storeReply'])->name('ticket.reply');
Route::post('ticket/{ticket}/upload', [TicketController::class, 'uploadImage'])->name('ticket.upload');
Route::delete('ticket/{ticket}/attachment/{attachment}', [TicketController::class, 'deleteImage'])->name('ticket.delete-image');
Route::patch('ticket/{ticket}/status', [TicketController::class, 'updateStatus'])->name('ticket.update-status');

// User Report
Route::get('report', [ReportController::class, 'index'])->name('report.index');
Route::get('report/export', [ReportController::class, 'export'])->name('report.export');

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
    Route::prefix('prabayar')->name('prabayar.')->group(function () {
        Route::get('', [PrabayarController::class, 'index'])->name('index');
        Route::get('get-services-digiflazz', [PrabayarController::class, 'getServicesDigiflazz'])->name('getServicesDigiflazz');
        Route::get('get-provider', [PrabayarController::class, 'getProvider'])->name('getProvider');
        Route::get('get-type', [PrabayarController::class, 'getType'])->name('getType');
        Route::get('get-services', [PrabayarController::class, 'getServices'])->name('getServices');
        Route::get('get-services/{id}', [PrabayarController::class, 'detailServices'])->name('detailServices');
        Route::delete('delete-services', [PrabayarController::class, 'deleteAllServices'])->name('deleteServices');
        Route::get('{buyer_sku_code}', [PrabayarController::class, 'show'])->name('show');
    });

    // Pascabayar
    Route::prefix('pascabayar')->name('pascabayar.')->group(function () {
        Route::get('', [PascabayarController::class, 'index'])->name('index');
        Route::get('get-services-digiflazz', [PascabayarController::class, 'getServicesDigiflazz'])->name('getServicesDigiflazz');
        Route::get('get-product', [PascabayarController::class, 'getProduct'])->name('getProduct');
        Route::delete('delete-services', [PascabayarController::class, 'deleteAllServices'])->name('deleteServices');
    });

    // Premium Account
    Route::prefix('premium-account')->name('premium-account.')->group(function () {
        Route::get('', [PremiumAccountController::class, 'index'])->name('index');
    });

    // Premium Order
    Route::prefix('premium-order')->name('premium-order.')->group(function () {
        Route::get('{id}', [App\Http\Controllers\PremiumOrderController::class, 'show'])->name('show');
        Route::post('{id}', [App\Http\Controllers\PremiumOrderController::class, 'store'])->name('store');
    });
});

// Level Upgrade
Route::prefix('level-upgrade')->name('level-upgrade.')->group(function () {
    Route::get('', [App\Http\Controllers\LevelUpgradeController::class, 'index'])->name('index');
    Route::post('', [App\Http\Controllers\LevelUpgradeController::class, 'store'])->name('store');
});

// Register Agen
Route::prefix('agen')->name('agen.')->group(function () {
    Route::get('', [RegisterAgenController::class, 'index'])->name('index');
    Route::post('', [RegisterAgenController::class, 'store'])->name('store');
});

// Voucher
Route::prefix('voucher')->name('voucher.')->group(function () {
    // Route::get('', [VoucherController::class, 'index'])->name('index');
});

// History Transaction
Route::prefix('history')->name('history.')->group(function () {
    Route::get('prabayar', [PrabayarController::class, 'history'])->name('prabayar');
    Route::post('prabayar/print', [PrabayarController::class, 'print'])->name('prabayar.print');
    Route::post('prabayar/send-invoice-whatsapp', [PrabayarController::class, 'sendInvoicePrabayar'])->name('prabayar.send.invoice');
    Route::get('prabayar/{invoice}', [PrabayarController::class, 'historyDetail'])->name('prabayar.detail');

    Route::get('pascabayar', [PascabayarController::class, 'history'])->name('pascabayar');

    Route::get('premium-account', [TransactionGameFeatureController::class, 'history'])->name('premium-account');
    Route::get('premium-account/{invoice}', [TransactionGameFeatureController::class, 'historyDetail'])->name('history-premium-account');
});

// Report
Route::prefix('report')->name('report.')->group(function () {
    Route::get('transactions', [ReportController::class, 'transactions'])->name('transactions');
});

// Transaction
Route::post('', [TransactionController::class, 'store'])->name('trx.store');
Route::prefix('transaction')->group(function () {
    Route::post('', [TransactionController::class, 'pascabayar'])->name('trx.pascabayar');
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

// Orders Pascabayar
Route::prefix('pascabayar')->name('pascabayar.')->group(function () {
    // PLN
    Route::get('pln', [OrderPascaController::class, 'pln'])->name('pln');
});
