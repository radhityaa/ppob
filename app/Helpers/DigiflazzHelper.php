<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DigiflazzHelper
{
    public static function getMode()
    {
        return env('DIGIFLAZZ_MODE');
    }

    public static function getUsername()
    {
        return env('DIGIFLAZZ_USERNAME');
    }

    public static function getKey()
    {
        return env('DIGIFLAZZ_KEY');
    }

    public static function getWebhookId()
    {
        return env('DIGIFLAZZ_WEBHOOK_ID');
    }

    public static function getWebhookUrl()
    {
        return env('DIGIFLAZZ_WEBHOOK_URL');
    }

    public static function getWebhookSecret()
    {
        return env('DIGIFLAZZ_WEBHOOK_SECRET');
    }

    public static function getSign(string $type)
    {
        return md5(self::getUsername() . self::getKey() . $type);
    }

    public static function getService(string $type)
    {
        $data = [
            'cmd' => $type,
            'username' => self::getUsername(),
            'sign' => self::getSign('pricelist')
        ];

        return self::transaction('price-list', $data);
    }

    public static function transaction(string $url, array $data)
    {
        $response = Http::post('https://api.digiflazz.com/v1/' . $url, $data);
        return json_decode($response);
    }

    public static function validasiTokenPln($target)
    {
        $data = [
            'commands' => 'pln-subscribe',
            'customer_no' => $target
        ];

        return self::transaction('transaction', $data);
    }
}
