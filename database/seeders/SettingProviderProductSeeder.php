<?php

namespace Database\Seeders;

use App\Models\SettingProviderProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingProviderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingProviderProduct::create([
            'user_id' => 1,
            'setting_provider_id' => 2,
        ]);
    }
}
