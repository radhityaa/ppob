<?php

namespace App\Services;

use App\Helpers\DigiflazzHelper;
use App\Models\Prabayar;
use App\Models\SettingMargin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DigiflazzSyncService
{
    protected $digiflazzHelper;

    public function __construct()
    {
        $this->digiflazzHelper = new DigiflazzHelper();
    }

    /**
     * Sync all products from Digiflazz
     */
    public function syncAllProducts()
    {
        $categories = [
            'pulsa' => 'Pulsa',
            'data' => 'Data',
            'e-wallet' => 'E-Wallet',
            'pln' => 'PLN',
            'pdam' => 'PDAM',
            'pbb' => 'PBB',
            'bpjs' => 'BPJS',
            'telkom' => 'Telkom',
            'indihome' => 'Indihome',
            'multifinance' => 'Multifinance',
            'game' => 'Game',
            'voucher' => 'Voucher'
        ];

        $results = [
            'total_created' => 0,
            'total_updated' => 0,
            'total_errors' => 0,
            'categories' => []
        ];

        foreach ($categories as $category => $categoryName) {
            try {
                $result = $this->syncCategory($category, $categoryName);
                $results['categories'][$category] = $result;
                $results['total_created'] += $result['created'];
                $results['total_updated'] += $result['updated'];
                $results['total_errors'] += $result['errors'];
            } catch (\Exception $e) {
                Log::error("Digiflazz sync failed for {$categoryName}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                $results['categories'][$category] = [
                    'created' => 0,
                    'updated' => 0,
                    'errors' => 1,
                    'error' => $e->getMessage()
                ];
                $results['total_errors']++;
            }
        }

        // Track last sync time
        Cache::put('digiflazz_sync_last_run', now(), 3600);

        return $results;
    }

    /**
     * Sync specific category
     */
    public function syncCategory($category, $categoryName = null)
    {
        if (!$categoryName) {
            $categoryName = ucfirst($category);
        }

        $created = 0;
        $updated = 0;
        $errors = 0;

        try {
            // Get products from Digiflazz API
            $products = $this->digiflazzHelper->getProducts($category);

            if (empty($products)) {
                Log::warning("No products found for {$categoryName}");
                return ['created' => 0, 'updated' => 0, 'errors' => 0];
            }

            foreach ($products as $productData) {
                try {
                    $result = $this->syncProduct($productData, $category);
                    if ($result === 'created') {
                        $created++;
                    } elseif ($result === 'updated') {
                        $updated++;
                    }
                } catch (\Exception $e) {
                    $errors++;
                    Log::error("Failed to sync product {$productData['buyer_sku_code']}", [
                        'product' => $productData,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error("Digiflazz sync failed for {$categoryName}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return ['created' => $created, 'updated' => $updated, 'errors' => $errors];
    }

    /**
     * Sync individual product
     */
    public function syncProduct($productData, $category)
    {
        $existingProduct = Prabayar::where('buyer_sku_code', $productData['buyer_sku_code'])->first();

        if ($existingProduct) {
            return $this->updateProduct($existingProduct, $productData);
        } else {
            return $this->createProduct($productData, $category);
        }
    }

    /**
     * Update existing product
     */
    private function updateProduct($existingProduct, $productData)
    {
        $settingMarginMember = SettingMargin::where('slug', 'member')->first();
        $settingMarginReseller = SettingMargin::where('slug', 'reseller')->first();
        $settingMarginAgen = SettingMargin::where('slug', 'agen')->first();

        $marginMember = $settingMarginMember['margin'];
        $marginReseller = $settingMarginReseller['margin'];
        $marginAgen = $settingMarginAgen['margin'];

        $hasChanges = false;
        $changes = [];

        // Check for price changes
        if ($existingProduct->price != $productData['price']) {
            $changes['price'] = [
                'old' => $existingProduct->price,
                'new' => $productData['price']
            ];
            $existingProduct->price = $productData['price'];
            $hasChanges = true;
        }

        // Check for price_member changes
        if ($existingProduct->price_member != $productData['price'] + $marginMember) {
            $changes['price_member'] = [
                'old' => $existingProduct->price_member,
                'new' => $productData['price'] + $marginMember
            ];
            $existingProduct->price_member = $productData['price'] + $marginMember;
            $hasChanges = true;
        }

        // Check for price_reseller changes
        if ($existingProduct->price_reseller != $productData['price'] + $marginReseller) {
            $changes['price_reseller'] = [
                'old' => $existingProduct->price_reseller,
                'new' => $productData['price'] + $marginReseller
            ];
            $existingProduct->price_reseller = $productData['price'] + $marginReseller;
            $hasChanges = true;
        }

        // Check for price_agen changes
        if ($existingProduct->price_agen != $productData['price'] + $marginAgen) {
            $changes['price_agen'] = [
                'old' => $existingProduct->price_agen,
                'new' => $productData['price'] + $marginAgen
            ];
            $existingProduct->price_agen = $productData['price'] + $marginAgen;
            $hasChanges = true;
        }

        // Check for seller_product_status changes
        if ($existingProduct->seller_product_status != $productData['seller_product_status']) {
            $changes['seller_product_status'] = [
                'old' => $existingProduct->seller_product_status,
                'new' => $productData['seller_product_status']
            ];
            $existingProduct->seller_product_status = $productData['seller_product_status'];
            $hasChanges = true;
        }

        // Check for other field changes
        $fieldsToCheck = ['product_name', 'category', 'type', 'brand', 'desc'];
        foreach ($fieldsToCheck as $field) {
            if (isset($productData[$field]) && $existingProduct->$field != $productData[$field]) {
                $changes[$field] = [
                    'old' => $existingProduct->$field,
                    'new' => $productData[$field]
                ];
                $existingProduct->$field = $productData[$field];
                $hasChanges = true;
            }
        }

        if ($hasChanges) {
            $existingProduct->save();

            // Log the changes
            Log::info("Product updated: {$productData['buyer_sku_code']}", [
                'product' => $productData['buyer_sku_code'],
                'changes' => $changes
            ]);

            return 'updated';
        }

        return 'no_changes';
    }

    /**
     * Create new product
     */
    private function createProduct($productData, $category)
    {
        $settingMarginMember = SettingMargin::where('slug', 'member')->first();
        $settingMarginReseller = SettingMargin::where('slug', 'reseller')->first();
        $settingMarginAgen = SettingMargin::where('slug', 'agen')->first();

        $marginMember = $settingMarginMember['margin'];
        $marginReseller = $settingMarginReseller['margin'];
        $marginAgen = $settingMarginAgen['margin'];

        $newProduct = new Prabayar();
        $newProduct->buyer_sku_code = $productData['buyer_sku_code'];
        $newProduct->product_name = $productData['product_name'] ?? '';
        $newProduct->category = $productData['category'] ?? $category;
        $newProduct->type = $productData['type'] ?? '';
        $newProduct->brand = $productData['brand'] ?? '';
        $newProduct->price = $productData['price'];
        $newProduct->price_member = $productData['price'] + $marginMember;
        $newProduct->price_reseller = $productData['price'] + $marginReseller;
        $newProduct->price_agen = $productData['price'] + $marginAgen;
        $newProduct->seller_name = $productData['seller_name'] ?? '';
        $newProduct->seller_product_status = $productData['seller_product_status'];
        $newProduct->buyer_product_status = $productData['buyer_product_status'];
        $newProduct->unlimited_stock = $productData['unlimited_stock'];
        $newProduct->stock = $productData['stock'];
        $newProduct->multi = $productData['multi'];
        $newProduct->start_cut_off = $productData['start_cut_off'];
        $newProduct->end_cut_off = $productData['end_cut_off'];
        $newProduct->desc = $productData['desc'];
        $newProduct->provider = 'digiflazz';
        $newProduct->save();

        Log::info("New product created: {$productData['buyer_sku_code']}", [
            'product' => $productData
        ]);

        return 'created';
    }

    /**
     * Get sync statistics
     */
    public function getSyncStats()
    {
        $cacheKey = 'digiflazz_sync_stats';

        return Cache::remember($cacheKey, 3600, function () {
            return [
                'total_products' => Prabayar::count(),
                'active_products' => Prabayar::where('buyer_product_status', true)->count(),
                'inactive_products' => Prabayar::where('buyer_product_status', false)->count(),
                'last_sync' => Prabayar::max('updated_at'),
                'categories' => Prabayar::selectRaw('category, COUNT(*) as count')
                    ->groupBy('category')
                    ->pluck('count', 'category')
                    ->toArray()
            ];
        });
    }

    /**
     * Clear sync cache
     */
    public function clearCache()
    {
        Cache::forget('digiflazz_sync_stats');
    }
}
