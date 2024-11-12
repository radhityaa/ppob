<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class TelegramBotController extends Controller
{
    protected $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function webhook(Request $request)
    {
        $response = $this->telegram->getWebhookUpdate();
        $message = $response->message->text;
        $chatId = $response->message->chat->id;

        if (strtolower($message) === 'saldo') {
            $response = $this->checkSaldo();

            $this->telegram->reply([
                'chat_id' => $chatId,
                'text' => $response,
            ]);
        } else {
            $this->telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Perintah tidak dikenali.',
            ]);
        }
    }

    private function checkSaldo()
    {
        $saldo = 1000000;

        return 'Saldo anda saat ini adalah Rp.' . number_format($saldo, 0, '.', '.') . '.';
    }
}
