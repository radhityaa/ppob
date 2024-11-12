<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RechargeItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['recharge_title_id' => 1, 'route' => 'home', 'src' => 'pulsa.png', 'label' => 'Pulsa'],
            ['recharge_title_id' => 1, 'route' => 'home', 'src' => 'paket-data.png', 'label' => 'Kuota'],
            ['recharge_title_id' => 1, 'route' => 'home', 'src' => 'token-pln.png', 'label' => 'Token PLN'],
        ])->each(fn ($item) => \App\Models\RechargeItem::create($item));
    }
}
