<?php

namespace Database\Seeders;

use App\Models\SettingPaymentGateway;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingPaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingPaymentGateway::create([
            'setting_provider_id' => 1,
        ]);
    }
}
