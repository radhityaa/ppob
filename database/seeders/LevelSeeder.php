<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'member',
                'display_name' => 'Member',
                'description' => 'Level default untuk semua user dengan harga standar',
                'sort_order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'reseller',
                'display_name' => 'Reseller',
                'description' => 'Level reseller dengan harga lebih murah untuk bisnis',
                'sort_order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'agen',
                'display_name' => 'Agen',
                'description' => 'Level agen dengan harga terendah untuk distributor',
                'sort_order' => 3,
                'is_active' => true
            ]
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(
                ['name' => $level['name']],
                $level
            );
        }
    }
}
