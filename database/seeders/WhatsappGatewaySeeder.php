<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WhatsappGateway;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsappGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('username', 'admin123')->first();
        $reseller = User::where('username', 'reseller')->first();
        $agen = User::where('username', 'agen123')->first();
        $member = User::where('username', 'member')->first();

        collect([
            [
                'user_id' => $admin->id,
                'phone' => $admin->phone,
                'status' => 'Disconnected',
            ],
            [
                'user_id' => $reseller->id,
                'phone' => $reseller->phone,
                'status' => 'Disconnected',
            ],
            [
                'user_id' => $agen->id,
                'phone' => $agen->phone,
                'status' => 'Disconnected',
            ],
            [
                'user_id' => $member->id,
                'phone' => $member->phone,
                'status' => 'Disconnected',
            ],
        ])->each(fn ($data) => WhatsappGateway::create($data));
    }
}
