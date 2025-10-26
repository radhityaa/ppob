<?php

namespace App\Helpers;

use App\Models\SettingProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigiflazzHelper
{
    public static function getData()
    {
        return SettingProvider::where('name', 'digiflazz')->first();
    }

    public static function getMode()
    {
        return self::getData()->mode;
    }

    public static function getUsername()
    {
        return self::getData()->username;
    }

    public static function getKey()
    {
        return self::getData()->api_key;
    }

    public static function getWebhookId()
    {
        return self::getData()->webhook_id;
    }

    public static function getWebhookUrl()
    {
        return self::getData()->webhook_url;
    }

    public static function getWebhookSecret()
    {
        return self::getData()->webhook_secret;
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
        $response = Http::withOptions(['verify' => false])->post('https://api.digiflazz.com/v1/' . $url, $data);
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

    /**
     * Get products from Digiflazz API
     */
    public function getProducts($category = null)
    {
        try {
            $data = [
                'cmd' => 'pricelist',
                'username' => self::getUsername(),
                'sign' => self::getSign('pricelist')
            ];

            if ($category) {
                $data['category'] = $category;
            }

            $response = self::transaction('price-list', $data);

            if (!$response || !isset($response->data)) {
                return [];
            }

            // Transform response to array format
            $products = [];
            foreach ($response->data as $product) {
                $products[] = [
                    'buyer_sku_code' => $product->buyer_sku_code ?? '',
                    'product_name' => $product->product_name ?? '',
                    'category' => $product->category ?? $category,
                    'type' => $product->type ?? '',
                    'seller_name' => $product->seller_name ?? '',
                    'brand' => $product->brand ?? '',
                    'price' => $product->price ?? 0,
                    'seller_product_status' => $product->seller_product_status ?? 1,
                    'buyer_product_status' => $product->buyer_product_status ?? 'active',
                    'unlimited_stock' => $product->unlimited_stock ?? false,
                    'stock' => $product->stock ?? 0,
                    'multi' => $product->multi ?? false,
                    'start_cut_off' => $product->start_cut_off ?? '',
                    'end_cut_off' => $product->end_cut_off ?? '',
                    'desc' => $product->desc ?? '',
                ];
            }

            return $products;
        } catch (\Exception $e) {
            Log::error('Digiflazz getProducts failed', [
                'category' => $category,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get all product categories
     */
    public function getCategories()
    {
        try {
            $data = [
                'cmd' => 'pricelist',
                'username' => self::getUsername(),
                'sign' => self::getSign('pricelist')
            ];

            $response = self::transaction('price-list', $data);

            if (!$response || !isset($response->data)) {
                return [];
            }

            $categories = [];
            foreach ($response->data as $product) {
                if (isset($product->category) && !in_array($product->category, $categories)) {
                    $categories[] = $product->category;
                }
            }

            return $categories;
        } catch (\Exception $e) {
            Log::error('Digiflazz getCategories failed', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}
