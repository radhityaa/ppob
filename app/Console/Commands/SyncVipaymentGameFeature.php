<?php

namespace App\Console\Commands;

use App\Helpers\VipaymentHelper;
use App\Models\SettingMargin;
use App\Models\VipGameStreaming;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncVipaymentGameFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vipayment:sync-game-feature';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync game feature from Vipayment API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Vipayment game feature sync...');

        // Nanti di bedakan margin nya khusus vipayment
        $settings = SettingMargin::whereIn('slug', [
            'vip-premium-member',
            'vip-premium-reseller',
            'vip-premium-agen'
        ])->get()->keyBy('slug');

        $marginMember = $settings['vip-premium-member']['margin'] ?? 0;
        $marginReseller = $settings['vip-premium-reseller']['margin'] ?? 0;
        $marginAgen = $settings['vip-premium-agen']['margin'] ?? 0;

        $services = VipaymentHelper::getServices('game');

        if (!$services['result']) {
            $this->error($services['message']);
            Log::error($services['message']);
            return Command::FAILURE;
        }

        $data = $services['data'];
        if (isset($data) && is_array($data)) {
            $gameFeatureCodes = [];

            foreach ($data as $item) {
                $gameFeatureCodes[] = $item['code'];

                VipGameStreaming::updateOrCreate(
                    ['code' => $item['code']],
                    [
                        'code' => $item['code'],
                        'game' => $item['game'],
                        'name' => $item['name'],
                        'price' => $item['price']['basic'],
                        'price_member' => (is_array($item['price']['basic']) ? 0 : (float)$item['price']['basic']) + (float)$marginMember,
                        'price_agen' => (is_array($item['price']['basic']) ? 0 : (float)$item['price']['basic']) + (float)$marginAgen,
                        'price_reseller' => (is_array($item['price']['basic']) ? 0 : (float)$item['price']['basic']) + (float)$marginReseller,
                        'server' => $item['server'],
                        'status' => $item['status'] == 'empty' ? false : true,
                    ]
                );
            }

            VipGameStreaming::whereNotIn('code', $gameFeatureCodes)->delete();

            $this->info('Vipayment game feature synced successfully');
            Log::info('Vipayment game feature synced successfully');
            return Command::SUCCESS;
        } else {
            $this->error('No data found from Vipayment game feature API');
            Log::error('No data found from Vipayment game feature API');
            return Command::FAILURE;
        }
    }
}
