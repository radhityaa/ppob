/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : ppob

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 28/07/2024 15:07:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for message_templates
-- ----------------------------
DROP TABLE IF EXISTS `message_templates`;
CREATE TABLE `message_templates`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of message_templates
-- ----------------------------
INSERT INTO `message_templates` VALUES (1, 'deposit-manual-user', 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\n> Data Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n_Harap Dibayarkan Dihari Yang Sama, Jika Tidak Makan Deposit Akan DiBatalkan Otomatis Oleh Sistem._\r\nBayar Sebelum *{{expired_at}}*\r\n\r\n*Apabila Sudah Melakukan Pembayaran, SIlahkan Kirim Bukti Transfer nya Ke Sini*\r\n\r\nAbaikan Pesan Ini Jika Sudah Melakukan Pembayaran\r\n\r\n*Terima Kasih*', 'Notifikasi Deposit Manual Ke User', '2024-06-18 11:07:02', '2024-07-28 08:04:29');
INSERT INTO `message_templates` VALUES (2, 'deposit-manual-admin', 'Hi, Admin\r\n\r\nPengguna Dengan Username *{{username}}* Telah Melakukan Deposit Saldo Manual.\r\n\r\n> Detail Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n> Detail User:\r\n- Nama: *{{name}}*\r\n- Username: *{{username}}*\r\n- No.HP: *{{phone}}*\r\n- Email: *{{email}}*\r\n- Nama Toko: *{{shop_name}}*\r\n- Alamat: *{{address}}*\r\n- Saldo: *{{saldo}}*\r\n\r\nBerikut URL Untuk Informasi Deposit: {{url}}\r\n\r\n*Terima Kasih*', 'Notifikasi Ada Request Deposit Manual Ke Admin', '2024-06-18 11:07:08', '2024-06-18 06:58:29');
INSERT INTO `message_templates` VALUES (3, 'deposit-otomatic-user', 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\n> Data Deposit:\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\n_Harap Dibayarkan Dihari Yang Sama, Jika Tidak Makan Deposit Akan DiBatalkan Otomatis Oleh Sistem._\r\nBayar Sebelum *{{expired_at}}*\r\n\r\nAbaikan Pesan Ini Jika Sudah Melakukan Pembayaran\r\n\r\n*Terima Kasih*', 'Notifikasi Deposit Otomatis Ke User', '2024-06-18 11:08:04', '2024-06-18 06:57:00');
INSERT INTO `message_templates` VALUES (4, 'deposit-notification-user', 'Hi, *_{{name}}_*\r\n\r\nTerima Kasih Telah Melakukan Deposit Di *{{app_name}}*.\r\n\r\nPembayaran Deposit Dengan Detail Berikut:\r\n\r\n- Invoice: *{{invoice}}*\r\n- Pembayaran: *{{method}}*\r\n- Kode Pembayaran: *{{pay_code}}*\r\n- URL Pembayaran: *{{pay_url}}*\r\n- Checkout Pembayaran: *{{checkout_url}}*\r\n- Nominal: *{{nominal}}*\r\n- Fee: *{{fee}}*\r\n- Saldo Harus Dibayarkan: *{{total}}*\r\n- Saldo Diterima: *{{amount_received}}*\r\n\r\nSudah Kami Terima, Status Deposit *{{status}}*, Telah Dibayar Pada *{{paid_at}}*.\r\n\r\n*Terima Kasih*', 'Notifikasi Status Deposit Ke User', '2024-06-18 13:58:59', '2024-06-18 07:02:02');
INSERT INTO `message_templates` VALUES (5, 'transaction-notification-user', '*{{shop_name}}*\r\n```{{address}}```\r\n```===========================================```\r\n```Tanggal: {{created_at}}\r\nInvoice: {{invoice}}\r\nLayanan: {{product_name}}\r\nTujuan: {{target}}\r\nHarga: {{price}}\r\nStatus: {{status}}\r\nSN: {{sn}}\r\nKeterangan: {{message}}```\r\n```===========================================```\r\nTerima Kasih Sudah Melakukan Transaksi Di *{{shop_name}}*.\r\n\r\n```Tersedia Pulsa, Kuota, E-Money, Token PLN, Bayar Listrik, PDAM, Telkom, Internet, Wifi, Voucher Data, Topup Game Dan Pembayaran Lainnya.```', 'Notifikasi Transaksi Ke Pelanggan', '2024-06-18 14:33:18', '2024-06-18 08:50:22');

SET FOREIGN_KEY_CHECKS = 1;
