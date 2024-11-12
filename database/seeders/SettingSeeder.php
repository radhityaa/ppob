<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create([
            'name' => 'Informasi Deposit',
            'slug' => 'settings-information-deposit',
            'description' => 'Setting Informasi deposit',
            'val1' => '<ol><li>Masukkan jumlah deposit.</li><li>Pilih jenis pembayaran yang Anda inginkan, tersedia 4 opsi.</li><li>Pilih metode pembayaran yang Anda inginkan.</li><li>Klik&nbsp;<strong>deposit</strong>&nbsp;untuk permintaan deposit</li></ol>',
            'val2' => '<ul><li>Virtual Account Otomatis Open 24 Jam.</li><li>QRIS Otomatis Open 00:00 - 23:00 WIB.</li><li>BCA Otomatis Open 06:00 - 21:00 WIB.</li><li>BRI, BNI Manual Open 09:00 - 22:00 WIB</li></ul>',
        ]);
    }
}
