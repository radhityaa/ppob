<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TripayHelper
{
    public static function getPrivateKey()
    {
        return env('TRIPAY_PRIVATE_KEY');
    }

    public static function getApiKey()
    {
        return env('TRIPAY_API_KEY');
    }

    public static function generateSignature($merchantRef, $amount)
    {
        $privateKey = self::getPrivateKey();
        $merchantCode = env('TRIPAY_CODE_MERCHANT');

        return hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);
    }

    public static function getChannels()
    {
        $response = Http::withToken(self::getApiKey())->get('https://tripay.co.id/api-sandbox/merchant/payment-channel');
        return json_decode($response->body(), true);
    }

    public static function createDepositLocal($nominal, $method, array $orderItems)
    {
        $invoice = invoice(Auth::user()->id, 'TRX');
        $signature = self::generateSignature($invoice, $nominal);

        $data = [
            'method' => $method,
            'merchant_ref' => $invoice,
            'amount' => $nominal,
            'customer_name' => Auth::user()->name,
            'customer_email' => Auth::user()->email,
            'customer_phone' => Auth::user()->phone,
            'order_items' => $orderItems,
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => $signature
        ];

        $response = Http::withToken(self::getApiKey())->post('https://tripay.co.id/api-sandbox/transaction/create', $data);
        return json_decode($response->body());
    }
}
