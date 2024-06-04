<?php

namespace Database\Seeders;

use App\Models\Landingpage\Hero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hero::create([
            'title' => 'AyasyaTech | Pusat Reseller dan H2H PPOB & Topup',
            'description' => 'Platform reseller h2h yang menyediakan Layanan Pulsa, PPOB, Topup Game dll, termurah dan terlengkap',
            'button_text' => 'Mulai Berjualan',
            'button_url' => '/#landingPricing',
            'small_text' => 'Join Sekarang!',
            'image_hero_dashboard' => 'hero-dashboard-light.png',
            'image_hero_dashboard_dark' => 'hero-dashboard-dark.png',
            'image_hero_element' => 'hero-elements-light.png',
            'image_hero_element_dark' => 'hero-elements-dark.png',
        ]);
    }
}
