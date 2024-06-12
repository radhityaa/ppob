<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TripayHelper
{
    public static function getMode()
    {
        $mode = env('TRIPAY_MODE');
        if ($mode == 'dev') {
            return 'https://tripay.co.id/api-sandbox';
        } else if ($mode == 'prod') {
            return 'https://tripay.co.id/api';
        }
    }

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
        $mode = self::getMode();
        $response = Http::withToken(self::getApiKey())->get($mode . '/merchant/payment-channel');
        return json_decode($response->body(), true);
    }

    public static function createDepositLocal($nominal, $method, array $orderItems)
    {
        try {
            $mode = self::getMode();
            $invoice = invoice(Auth::user()->id, 'DPS');
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

            $response = Http::withToken(self::getApiKey())->post($mode . '/transaction/create', $data);
            return json_decode($response->body());
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }

    public static function terbilang($number)
    {
        $units = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if ($number < 12) {
            return $units[$number];
        } elseif ($number < 20) {
            return $units[$number - 10] . " belas";
        } elseif ($number < 100) {
            return $units[(int)($number / 10)] . " puluh " . self::terbilang($number % 10);
        } elseif ($number < 200) {
            return "seratus " . self::terbilang($number - 100);
        } elseif ($number < 1000) {
            return $units[(int)($number / 100)] . " ratus " . self::terbilang($number % 100);
        } elseif ($number < 2000) {
            return "seribu " . self::terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return self::terbilang((int)($number / 1000)) . " ribu " . self::terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return self::terbilang((int)($number / 1000000)) . " juta " . self::terbilang($number % 1000000);
        } else {
            return "nomor terlalu besar";
        }
    }
}
