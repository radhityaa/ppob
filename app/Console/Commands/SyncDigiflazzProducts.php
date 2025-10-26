<?php

namespace App\Console\Commands;

use App\Helpers\DigiflazzHelper;
use App\Models\Prabayar;
use App\Models\SettingMargin;
use Illuminate\Console\Command;
use App\Services\DigiflazzSyncService;
use Illuminate\Support\Facades\Log;

class SyncDigiflazzProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'digiflazz:sync-products {--force : Force sync even if products exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products from Digiflazz API and update prices/status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Digiflazz products sync...');

        $settings = SettingMargin::whereIn('slug', [
            'member',
            'reseller',
            'agen'
        ])->get()->keyBy('slug');

        $marginMember = $settings['member']['margin'] ?? 0;
        $marginReseller = $settings['reseller']['margin'] ?? 0;
        $marginAgen = $settings['agen']['margin'] ?? 0;

        $result = DigiflazzHelper::getService('prepaid');

        if (isset($result->data)) {
            // Jika respons mengandung kesalahan
            if (isset($result->data->rc) && $result->data->rc !== "00") {
                $this->error($result->data->message ?? 'Terjadi kesalahan saat mengambil data dari Digiflazz.');
                Log::error($result->data->message ?? 'Terjadi kesalahan saat mengambil data dari Digiflazz.');
                return Command::FAILURE;
            }

            // Jika respons berhasil
            if (is_array($result->data)) {
                $digiflazzSkuCodes = [];

                foreach ($result->data as $item) {
                    $digiflazzSkuCodes[] = $item->buyer_sku_code;

                    Prabayar::updateOrCreate(
                        ['buyer_sku_code' => $item->buyer_sku_code],
                        [
                            'product_name' => $item->product_name,
                            'category' => $item->category,
                            'brand' => $item->brand,
                            'type' => $item->type,
                            'price' => $item->price,
                            'price_member' => $item->price + $marginMember,
                            'price_reseller' => $item->price + $marginReseller,
                            'price_agen' => $item->price + $marginAgen,
                            'seller_name' => $item->seller_name,
                            'buyer_sku_code' => $item->buyer_sku_code,
                            'buyer_product_status' => $item->buyer_product_status,
                            'seller_product_status' => $item->seller_product_status,
                            'unlimited_stock' => $item->unlimited_stock,
                            'stock' => $item->stock,
                            'multi' => $item->multi,
                            'start_cut_off' => $item->start_cut_off,
                            'end_cut_off' => $item->end_cut_off,
                            'desc' => $item->desc,
                            'provider' => 'digiflazz',
                        ]
                    );
                }

                Prabayar::whereNotIn('buyer_sku_code', $digiflazzSkuCodes)->delete();

                $this->info('Berhasil mengambil data.');
                Log::info('Berhasil mengambil data.');
                return Command::SUCCESS;
            } else {
                $this->error('Terjadi kesalahan saat mengambil data dari Digiflazz.');
                Log::error('Terjadi kesalahan saat mengambil data dari Digiflazz.');
                return Command::FAILURE;
            }
        } else {
            // Jika respons tidak memiliki data sama sekali
            $this->error('Terjadi kesalahan saat mengambil data dari Digiflazz.');
            Log::error('Terjadi kesalahan saat mengambil data dari Digiflazz.');
            return Command::FAILURE;
        }
        return Command::FAILURE;
    }
}
