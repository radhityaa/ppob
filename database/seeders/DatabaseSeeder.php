<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\SettingMargin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        SettingMargin::create(['margin' => 0]);

        $this->call([
            UserRolePermissionSeeder::class,
            NavigationSeeder::class,
            HeroSeeder::class,
            SettingProviderSeeder::class,
            SettingPaymentGatewaySeeder::class,
            SettingProviderProductSeeder::class,
            SettingSeeder::class,
            SettingMarginSeeder::class,
            SettingProfitSeeder::class,
            // MessageTemplateSeeder::class,
        ]);
    }
}
