<?php

namespace App\Console\Commands;

use App\Helpers\VipaymentHelper;
use App\Models\SettingMargin;
use App\Models\VipSocialMedia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncVipaymentSocialMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vipayment:sync-social-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync social media from Vipayment API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Vipayment social media sync...');

        // Nanti di bedakan margin nya khusus vipayment
        $settings = SettingMargin::whereIn('slug', [
            'vip-sosmed-member',
            'vip-sosmed-reseller',
            'vip-sosmed-agen'
        ])->get()->keyBy('slug');

        $marginMember = $settings['vip-sosmed-member']['margin'] ?? 0;
        $marginReseller = $settings['vip-sosmed-reseller']['margin'] ?? 0;
        $marginAgen = $settings['vip-sosmed-agen']['margin'] ?? 0;

        $services = VipaymentHelper::getServices('social-media');

        if (!$services['result']) {
            $this->error($services['message']);
            Log::error($services['message']);
            return Command::FAILURE;
        }

        $data = $services['data'];
        if (isset($data) && is_array($data)) {
            $idVipayment = [];

            foreach ($data as $item) {
                $idVipayment[] = $item['id'];

                VipSocialMedia::updateOrCreate(
                    ['id_vipayment' => $item['id']],
                    [
                        'id_vipayment' => $item['id'],
                        'category' => $item['category'],
                        'min' => $item['min'],
                        'max' => $item['max'],
                        'name' => $item['name'],
                        'note' => $item['note'],
                        'price' => $item['price']['basic'],
                        'price_member' => (is_array($item['price']['basic']) ? 0 : (float)$item['price']['basic']) + (float)$marginMember,
                        'price_agen' => (is_array($item['price']['basic']) ? 0 : (float)$item['price']['basic']) + (float)$marginAgen,
                        'price_reseller' => (is_array($item['price']['basic']) ? 0 : (float)$item['price']['basic']) + (float)$marginReseller,
                        'status' => $item['status'] == 'empty' ? false : true,
                    ]
                );
            }

            VipSocialMedia::whereNotIn('id_vipayment', $idVipayment)->delete();

            $this->info('Vipayment social media synced successfully');
            Log::info('Vipayment social media synced successfully');
            return Command::SUCCESS;
        } else {
            $this->error('No data found from Vipayment social media API');
            Log::error('No data found from Vipayment social media API');
            return Command::FAILURE;
        }
    }
}
