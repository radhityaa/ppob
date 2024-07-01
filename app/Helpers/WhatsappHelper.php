<?php

namespace App\Helpers;

use App\Models\MessageTemplate;
use App\Models\SettingProvider;
use App\Models\User;
use App\Models\WhatsappGateway;
use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    public static function getStatus()
    {
        return (bool) false;
    }

    public static function getApiKey()
    {
        $provider = SettingProvider::where('type', 'whatsapp_gateway')->first();
        return $provider->api_key;
    }

    public static function getNumberAdmin()
    {
        $number = env('APP_WA_ADMIN_NUMBER');
        return MyHelper::formatPhoneNumber($number);
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

    public static function sendMessage($templateName, $data, $target)
    {
        $apiKey = self::getApiKey();
        $adminNumber = self::getNumberAdmin();
        $template = MessageTemplate::where('type', $templateName)->first();
        $target = MyHelper::formatPhoneNumber($target);

        if (!$template) {
            throw new \Exception("Template not found.");
        }

        $message = TemplateHelper::render($template->message, $data);

        return Http::withHeaders([
            'accept' => 'application/json'
        ])->post(env('APP_WA_URL') . '/send-message', [
            'api_key' => $apiKey,
            'sender' => $adminNumber,
            'number' => $target,
            'message' => $message
        ]);
    }
}
