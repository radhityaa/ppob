<?php

namespace App\Helpers;

use App\Models\SettingProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class VipaymentHelper
{
    public static function getData()
    {
        return SettingProvider::where('name', 'vipayment')->first();
    }

    public static function getMode()
    {
        return self::getData()->mode;
    }

    public static function getApiKey()
    {
        return self::getData()->api_key;
    }

    public static function getApiId()
    {
        return self::getData()->code;
    }

    public static function getSign()
    {
        return self::getData()->username;
    }

    public static function getServices($type = 'prepaid')
    {
        switch ($type) {
            case 'prepaid':
                $url = 'prepaid';
                break;
            case 'game':
                $url = 'game-feature';
                break;
            case 'social-media':
                $url = 'social-media';
                break;
            default:
                $url = 'prepaid';
                break;
        }

        return self::connectService($url);
    }

    public static function connectService(string $url)
    {
        $key = self::getApiKey();
        $sign = self::getSign();

        $params = [
            'key' => $key,
            'sign' => $sign,
            'type' => 'services'
        ];

        $res = Http::withOptions(['verify' => false])
            ->asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ])
            ->post('https://vip-reseller.co.id/api/' . $url, $params)
            ->json();
        return $res;
    }

    public static function orderGameFeature($service, $dataNo = null, $dataZone = null)
    {
        $key = self::getApiKey();
        $sign = self::getSign();

        $params = [
            'key' => $key,
            'sign' => $sign,
            'type' => 'order',
            'service' => $service,
        ];

        if (!is_null($dataNo)) {
            $params['data_no'] = $dataNo;
        }

        if (!is_null($dataZone)) {
            $params['data_zone'] = $dataZone;
        }

        $res = Http::withOptions(['verify' => false])
            ->asForm()
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ])
            ->post('https://vip-reseller.co.id/api/game-feature', $params)
            ->json();
        return $res;
    }

    public static function invoiceGameFeature($userId)
    {
        $lastInvoice = DB::table('transaction_game_features')->orderBy('created_at', 'desc')->first();
        $invoiceNumber = $lastInvoice ? $lastInvoice->id + 1 : 1;
        return sprintf('GAME-' . '%06d', $invoiceNumber);
    }
}
