<?php

namespace Database\Seeders;

use App\Models\SettingProfit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingProfitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingProfit::create([
            'persentase' => 1,
            'minimal_withdrawal' => 10000,
        ]);
    }
}
