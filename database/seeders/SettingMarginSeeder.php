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
        ])->each(fn($q) => SettingMargin::create($q));
    }
}
