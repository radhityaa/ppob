<?php

namespace Database\Seeders;

use App\Models\SettingMargin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingMarginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Member', 'slug' => 'member', 'margin' => 0],
            ['name' => 'Agen', 'slug' => 'agen', 'margin' => 0],
            ['name' => 'Reseller', 'slug' => 'reseller', 'margin' => 0],
            ['name' => 'Member', 'slug' => 'vip-premium-member', 'margin' => 0],
            ['name' => 'Reseller', 'slug' => 'vip-premium-reseller', 'margin' => 0],
            ['name' => 'Agen', 'slug' => 'vip-premium-agen', 'margin' => 0],
            ['name' => 'Member', 'slug' => 'vip-sosmed-member', 'margin' => 0],
            ['name' => 'Reseller', 'slug' => 'vip-sosmed-reseller', 'margin' => 0],
            ['name' => 'Agen', 'slug' => 'vip-sosmed-agen', 'margin' => 0],
        ])->each(fn($q) => SettingMargin::create($q));
    }
}
