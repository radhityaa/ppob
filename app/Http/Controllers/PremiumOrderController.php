<?php

namespace App\Http\Controllers;

use App\Helpers\VipaymentHelper;
use App\Models\TransactionGameFeature;
use App\Models\VipGameStreaming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PremiumOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show order form for specific service
     */
    public function show($id)
    {
        $service = VipGameStreaming::findOrFail($id);
        $title = "Pemesanan " . $service->game;
        $user = Auth::user();

        // Get service-specific form fields
        $formFields = $this->getServiceFormFields($service->game);

        return view('premium-account.order', compact('service', 'user', 'formFields', 'title'));
    }

    /**
     * Process the order
     */
    public function store(Request $request, $id)
    {
        $service = VipGameStreaming::findOrFail($id);
        // $response = VipaymentHelper::orderGameFeature($service->code, $request->data_no, $request->data_zone);

        // if (!$response['result']) {
        //     return redirect()->back()
        //         ->with('error', $response['message']);
        // }

        // Simulate Response
        $response = [
            'result' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => [
                'trxid' => '1234567890',
                'status' => 'success',
                'note' => 'Pesanan berhasil dibuat'
            ]
        ];

        // Get Price 
        if (Auth::user()->hasRole('member')) {
            $price = $service->price_member;
        } else if (Auth::user()->hasRole('reseller')) {
            $price = $service->price_reseller;
        } else if (Auth::user()->hasRole('agen')) {
            $price = $service->price_agen;
        } else if (Auth::user()->hasRole('admin')) {
            $price = $service->price;
        } else {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk memesan layanan ini');
        }

        $invoice = VipaymentHelper::invoiceGameFeature(Auth::user()->id);
        $trxid = $response['data']['trxid'];
        $dataNo = $request->data_no;
        $dataZone = $request->data_zone;
        $originalPrice = $service->price;
        $sellingPrice = $price;
        $margin = $sellingPrice - $originalPrice;
        $status = $response['data']['status'];
        $note = $response['data']['note'];

        TransactionGameFeature::create([
            'user_id' => Auth::user()->id,
            'vip_game_streaming_id' => $service->id,
            'invoice' => $invoice,
            'trxid' => $trxid,
            'data_no' => $dataNo,
            'data_zone' => $dataZone,
            'status' => $status,
            'note' => $note,
            'original_price' => $originalPrice,
            'selling_price' => $sellingPrice,
            'margin' => $margin
        ]);

        return redirect()->back()
            ->with('success', $response['message']);
    }

    /**
     * Get form fields based on service type
     */
    private function getServiceFormFields($serviceName)
    {
        $serviceName = strtolower($serviceName);

        $formFields = [];

        switch (true) {
            case str_contains($serviceName, 'alight motion'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'number',
                        'label' => 'Nomor HP',
                        'placeholder' => 'Masukkan nomor',
                        'required' => true
                    ]
                ];
                $formFields['information'] = 'Masukan nomor Anda. Akun akan di kirim melalui Riwayat Pesanan dan jangan lupa untuk membaca ketentuan.';
                break;
            case str_contains($serviceName, 'amazon prime video'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'email',
                        'label' => 'Email Amazon Prime Video',
                        'placeholder' => 'Masukkan email Amazon Prime Video Anda',
                        'required' => true
                    ],
                    'data_zone' => [
                        'type' => 'text',
                        'label' => 'Profile',
                        'placeholder' => 'Request Profile',
                        'required' => false
                    ]
                ];
                $formFields['information'] = 'Masukan email Anda. Akun akan di kirim melalui Riwayat Pesanan dan jangan lupa untuk membaca ketentuan.';
                break;
            case str_contains($serviceName, 'disney hotstar'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'number',
                        'label' => 'Nomor HP',
                        'placeholder' => 'Masukkan nomor HP',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Masukan nomor whatsapp Anda. Nomor dan otp disney akan di kirim melalui whatsapp Admin.';
                $formFields['note'] = [
                    'Setelah membuat pesanan, konfirmasi ke Whatsapp Admin untuk minta Nomor dan Kode OTP Disney.'
                ];
                break;
            case str_contains($serviceName, 'getcontact premium'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'number',
                        'label' => 'Nomor Getcontact',
                        'placeholder' => 'Masukkan nomor Getcontact',
                        'required' => true
                    ],
                    'data_zone' => [
                        'type' => 'text',
                        'label' => 'Nama & Email',
                        'placeholder' => 'Masukkan Nama & Email Pengguna',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Untuk menemukan Nomor Getcontact Anda, Klik menu Lainnya lalu ke bagian Umum -> Pengaturan -> Pengaturan Akun dan tertera Nomor Telepon.';
                break;
            case str_contains($serviceName, 'netflix premium'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'text',
                        'label' => 'Nama Perangkat',
                        'placeholder' => 'Masukkan Nama Perangkat',
                        'required' => true
                    ],
                    'data_zone' => [
                        'type' => 'text',
                        'label' => 'Profile + PIN',
                        'placeholder' => 'Request Profile + PIN',
                        'required' => false
                    ],
                ];
                $formFields['information'] = 'Masukan Nama Devices Customer. Contoh : iPhone, iPad, Android, Tablet, Smart TV LG/Xiaomi/TCL dll.';
                $formFields['note'] = [
                    'Kolom ke-2 Request Profile + PIN 4 Digit.',
                    'Khusus Profile Shared 2 User (tidak bisa request profile dan pin random!).',
                    'Pembelian Profile 1 User dan 2 User Login Hanya Satu Devices!',
                    'WAJIB BACA KETENTUAN'
                ];
                break;
            case str_contains($serviceName, 'rcti plus'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'number',
                        'label' => 'Nomor HP',
                        'placeholder' => 'Masukkan Nomor HP',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Masukan nomor Anda. Akun akan di kirim melalui Riwayat Pesanan dan jangan lupa untuk membaca ketentuan.';
                break;
            case str_contains($serviceName, 'spotify premium'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'email',
                        'label' => 'Email',
                        'placeholder' => 'Masukkan Email',
                        'required' => true
                    ],
                    'data_zone' => [
                        'type' => 'text',
                        'label' => 'Profile Spotify',
                        'placeholder' => 'Masukkan Profile Spotify',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Masukan alamat email aktif Anda. Akun atau link invite akan di kirim melalui riwayat pesanan Anda. Untuk menemukan Nama Profile Spotify klik icon ⚙️ (pojok kanan atas) lalu tertera Nama Profile (bukan username).';
                $formFields['note'] = [
                    'Harap masukan Nama Profile Spotify!',
                    'REG INDO dan REG RANDOM sama saja tidak ada perbedaan'
                ];
                break;
            case str_contains($serviceName, 'vision plus'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'number',
                        'label' => 'Nomor HP',
                        'placeholder' => 'Masukkan Nomor HP',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Masukan nomor Anda. Akun akan di kirim melalui Riwayat Pesanan dan jangan lupa untuk membaca ketentuan.';
                break;
            case str_contains($serviceName, 'viu premium'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'number',
                        'label' => 'Nomor HP',
                        'placeholder' => 'Masukkan Nomor HP',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Masukan nomor Anda. Akun akan di kirim melalui Riwayat Pesanan dan jangan lupa untuk membaca ketentuan.';
                break;
            case str_contains($serviceName, 'youtube premium'):
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'text',
                        'label' => 'Email',
                        'placeholder' => 'Masukkan Email',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Masukan alamat email aktif Anda. Akun atau link invite akan di kirim melalui riwayat pesanan atau email Anda.';
                $formFields['note'] = [
                    'Khusus Gmail Customer input berupa email dan password dan pastikan gmail baru (fresh) agar mempermudah proses!',
                    'Admin Tampung Gmail Sebanyak-banyak nya untuk menjaga kestabilan Stock. Rate 1.500, Silahkan hubungi admin untuk info lebih lanjut.',
                ];
                break;
            default:
                // Default fields for other services
                $formFields['specific'] = [
                    'data_no' => [
                        'type' => 'email',
                        'label' => 'Email',
                        'placeholder' => 'Masukkan email',
                        'required' => true
                    ],
                ];
                $formFields['information'] = 'Masukan alamat email aktif Anda. Akun atau link invite akan di kirim melalui riwayat pesanan atau email Anda.';
                break;
        }

        return $formFields;
    }
}
