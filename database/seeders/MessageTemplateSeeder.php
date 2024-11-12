<?php

namespace Database\Seeders;

use App\Models\MessageTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['type' => 'transaction-notification-user', 'message' => '*{{shop_name}}*\r\n```{{address}}```\r\n```===========================================```\r\n```Tanggal: {{created_at}}\r\nInvoice: {{invoice}}\r\nLayanan: {{product_name}}\r\nTujuan: {{target}}\r\nHarga: {{price}}\r\nStatus: {{status}}\r\nSN: {{sn}}\r\nKeterangan: {{message}}```\r\n```===========================================```\r\nTerima Kasih Sudah Melakukan Transaksi Di *{{shop_name}}*.\r\n\r\n```Tersedia Pulsa, Kuota, E-Money, Token PLN, Bayar Listrik, PDAM, Telkom, Internet, Wifi, Voucher Data, Topup Game Dan Pembayaran Lainnya.```', 'description' => 'Notifikasi Transaksi ke pelanggan'],
            ['type' => 'deposit-notification-user', 'message' => 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\nPembayaran Deposit Dengan Detail Berikut:\r\n\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\nSudah Kami Terima, Status Deposit *{{status}}*, Telah Dibayar Pada *{{paid_at}}*.\r\n\r\n*Terima Kasih*', 'description' => 'Notifikasi Status Deposit Ke User'],
            ['type' => 'deposit-manual-user', 'message' => 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\n> Data Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n_Harap Dibayarkan Dihari Yang Sama, Jika Tidak Makan Deposit Akan DiBatalkan Otomatis Oleh Sistem._\r\nBayar Sebelum *{{expired_at}}*\r\n\r\n*Apabila Sudah Melakukan Pembayaran, SIlahkan Kirim Bukti Transfer nya Ke Sini*\r\n\r\nAbaikan Pesan Ini Jika Sudah Melakukan Pembayaran\r\n\r\n*Terima Kasih*', 'description' => 'Notifikasi Deposit Manual Ke User'],
            ['type' => 'deposit-manual-admin', 'message' => 'Hi, Admin\r\n\r\nPengguna Dengan Username *{{username}}* Telah Melakukan Deposit Saldo Manual.\r\n\r\n> Detail Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n> Detail User:\r\n- Nama: *{{name}}*\r\n- Username: *{{username}}*\r\n- No.HP: *{{phone}}*\r\n- Email: *{{email}}*\r\n- Nama Toko: *{{shop_name}}*\r\n- Alamat: *{{address}}*\r\n- Saldo: *{{saldo}}*\r\n\r\nBerikut URL Untuk Informasi Deposit: {{url}}\r\n\r\n*Terima Kasih*', 'description' => 'Notifikasi Ada Request Deposit Manual Ke Admin'],
            ['type' => 'deposit-otomatic-user', 'message' => 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\n> Data Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n_Harap Dibayarkan Dihari Yang Sama, Jika Tidak Makan Deposit Akan DiBatalkan Otomatis Oleh Sistem._\r\nBayar Sebelum *{{expired_at}}*\r\n\r\nAbaikan Pesan Ini Jika Sudah Melakukan Pembayaran\r\n\r\n*Terima Kasih*', 'description' => 'Notifikasi Deposit Otomatis Ke User'],
        ])->each(fn($q) => MessageTemplate::create($q));
    }
}
