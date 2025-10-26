<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Bot\TelegramBotController;

Route::post('tripay/callback', [WebhookController::class, 'callbackTripay'])->name('tripay.callback');
Route::post('digiflazz/callback', [WebhookController::class, 'callbackDigiflazz'])->name('digiflazz.callback');
Route::post('paydisini/callback', [WebhookController::class, 'callbackPaydisini'])->name('paydisini.callback');
Route::post('telegram/callback', [TelegramBotController::class, 'webhook'])->name('telegram.callback');
