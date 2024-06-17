<?php

namespace App\Helpers;

use App\Models\SettingProvider;
use App\Models\WhatsappGateway;
use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    public static function getApiKey()
    {
        $provider = SettingProvider::where('type', 'whatsapp_gateway')->first();
        return $provider->api_key;
    }

    public static function createDevice($user)
    {
        $apiKey = self::getApiKey();
        $number = $user->phone;

        Http::withHeaders([
            'accept' => 'application/json'
        ])->post(env('APP_WA_URL') . '/create-device', [
            'phone' => $number,
            'api_key' => $apiKey
        ]);

        return WhatsappGateway::create([
            'user_id' => $user->id,
            'phone' => $number,
            'status' => 'Disconnected',
        ]);
    }
}
