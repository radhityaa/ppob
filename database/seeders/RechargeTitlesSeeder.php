<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RechargeTitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['title' => 'Isi Ulang Sehari-hari'],
            ['title' => 'Dompet Digital'],
            ['title' => 'Bayar Tagihan'],
            ['title' => 'Produk Lainnya'],
        ])->each(fn ($item) => \App\Models\RechargeTitle::create($item));
    }
}
