-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 12, 2024 at 12:31 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ppob`
--

-- --------------------------------------------------------

--
-- Table structure for table `message_templates`
--

CREATE TABLE `message_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_templates`
--

INSERT INTO `message_templates` (`id`, `type`, `message`, `description`, `created_at`, `updated_at`) VALUES
(1, 'transaction-notification-user', '*{{shop_name}}*\r\n```{{address}}```\r\n\r\n```===========================================```\r\n```Tanggal: {{created_at}}\r\nInvoice: {{invoice}}\r\nLayanan: {{product_name}}\r\nTujuan: {{target}}\r\nHarga: {{price}}\r\nStatus: {{status}}\r\nSN: {{sn}}\r\nKeterangan: {{message}}```\r\n```===========================================```\r\n\r\nTerima Kasih Sudah Melakukan Transaksi Di *{{shop_name}}*.\r\n\r\n```Tersedia Pulsa, Kuota, E-Money, Token PLN, Bayar Listrik, PDAM, Telkom, Internet, Wifi, Voucher Data, Topup Game Dan Pembayaran Lainnya.```', 'Notifikasi Transaksi ke pelanggan', '2024-11-10 03:57:04', '2024-11-10 13:37:14'),
(2, 'deposit-notification-user', 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\nPembayaran Deposit Dengan Detail Berikut:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\nSudah Kami Terima, Status Deposit *{{status}}*, \r\nTelah Dibayar Pada *{{paid_at}}*.\r\n\r\n*Terima Kasih*', 'Notifikasi Status Deposit Otomatis', '2024-11-10 03:57:04', '2024-11-10 13:38:29'),
(3, 'deposit-manual-user', 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\n> Data Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n_Harap Dibayarkan Dihari Yang Sama, Jika Tidak Makan Deposit Akan DiBatalkan Otomatis Oleh Sistem._\r\n\r\nBayar Sebelum *{{expired_at}}*\r\n\r\n*Apabila Sudah Melakukan Pembayaran, SIlahkan Kirim Bukti Transfer nya Ke Sini*\r\n\r\n*Terima Kasih*', 'Notifikasi Deposit Manual Ke User', '2024-11-10 03:57:04', '2024-11-10 13:21:53'),
(4, 'deposit-manual-admin', 'Hi, *Admin*\r\n\r\nPengguna Dengan Username *{{username}}* Telah Melakukan Deposit Saldo Manual.\r\n\r\n> Detail Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n> Detail User:\r\n- Nama: *{{name}}*\r\n- Username: *{{username}}*\r\n- No.HP: *{{phone}}*\r\n- Email: *{{email}}*\r\n- Nama Toko: *{{shop_name}}*\r\n- Alamat: *{{address}}*\r\n- Saldo: *{{saldo}}*\r\n\r\nBerikut URL Untuk Informasi Deposit: {{url}}\r\n\r\n*Terima Kasih*', 'Notifikasi Ada Request Deposit Manual Ke Admin', '2024-11-10 03:57:04', '2024-11-10 13:22:10'),
(5, 'deposit-otomatic-user', 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\n> Data Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n_Harap Dibayarkan Dihari Yang Sama, Jika Tidak Makan Deposit Akan DiBatalkan Otomatis Oleh Sistem._\r\n\r\nBayar Sebelum *{{expired_at}}*\r\n\r\n*Terima Kasih*', 'Notifikasi Deposit Otomatis Ke User', '2024-11-10 03:57:04', '2024-11-10 13:39:44'),
(6, 'deposit-manual-notification-user', 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\nPembayaran Deposit Dengan Detail Berikut:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\nSudah Kami Terima, Status Deposit *{{status}}*, \r\nTelah Dibayar Pada *{{paid_at}}*.\r\n\r\n*Terima Kasih*', 'Notifikasi Status Deposit Manual Ke User', '2024-11-10 03:57:04', '2024-11-10 13:26:06'),
(7, 'otp', '*{{app_name}}*: Masukkan kode verifikasi: *{{otp}}*. Berlaku selama 5 menit.\r\nJANGAN beritahu kode ini ke siapa pun.', 'Kode OTP', '2024-11-10 03:57:04', '2024-11-10 16:22:54'),
(8, 'register-agen', 'Hallo, {{name}},\r\nSelamat bergabung di *{{app_name}}*!.\r\n\r\nAnda sudah menjadi Agen resmi *{{shop_name}}*.\r\nDidaftarkan oleh *{{reseller_name}}*.\r\n\r\n> Detail Akun Kamu:\r\n- Nama: {{name}}\r\n- Username: {{username}}\r\n- Email: {{email}}\r\n- Toko: {{shop_name}}\r\n- Alamat: {{address}}\r\n- Saldo: {{saldo}}\r\n- Password: {{password}}\r\n- Dibuat Tanggal: {{created_at}}\r\n\r\n*Perhatian*\r\n_Harap langsung ganti password telebih dahulu secepatnya!_\r\n\r\nTerima Kasih.', 'Notifikasi Pendaftaran Agen', '2024-11-10 03:57:04', '2024-11-11 05:10:59'),
(9, 'reset-password', 'Halo, *{{name}}*,\r\n\r\nKami menerima permintaan untuk mereset password akun Anda. Jika Anda tidak meminta reset ini, Anda bisa mengabaikan email ini.\r\n\r\nKlik link di bawah ini untuk mereset password Anda:\r\n\r\n{{url}}\r\n\r\nCatatan: Link ini hanya berlaku selama 5 menit.\r\n\r\nJika Anda mengalami masalah atau butuh bantuan lebih lanjut, silakan hubungi tim dukungan kami.\r\n\r\nTerima kasih,\r\n*{{app_name}}*', 'Notifikasi reset password', '2024-11-10 03:57:04', '2024-11-11 07:52:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message_templates`
--
ALTER TABLE `message_templates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message_templates`
--
ALTER TABLE `message_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
