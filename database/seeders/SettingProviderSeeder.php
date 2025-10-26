<?php

namespace Database\Seeders;

use App\Models\SettingProvider;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            [
                'name' => 'tripay',
                'slug' => Str::slug(Str::random()),
                'mode' => 'dev',
                'type' => 'payment_gateway',
                'api_key' => 'DEV-1TnkVMJheFh0QQl5IpGzo9EZ3RSnYPymCIm614FJ',
                'private_key' => 'pT37T-VbaCy-tPZqp-JhojK-LDLnS',
                'code' => 'T29295',
            ],
            [
                'name' => 'digiflazz',
                'slug' => Str::slug(Str::random()),
                'mode' => 'dev',
                'type' => 'product',
                'api_key' => 'dev-3446da70-e3a8-11eb-9cf1-bbae9ce189b4',
                'username' => 'rukasoDb7pkW',
                'webhook_id' => 'gdlEPg',
                'webhook_secret' => '5833b52b8375a2fe'
            ],
            [
                'name' => 'vipayment',
                'slug' => Str::slug(Str::random()),
                'mode' => 'dev',
                'type' => 'product',
                'api_key' => '17d738b8c59c3399ab88a8debd56540c',
                'code' => 'H2Wej1mb',
                'username' => 'eb086367a15ca81efb36236ae9ec9a99'
            ]
        ])->each(fn($data) => SettingProvider::create($data));
    }
}
