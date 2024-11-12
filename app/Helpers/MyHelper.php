<?php

namespace App\Helpers;

use App\Models\Prabayar;

class MyHelper
{
    public static function terbilang($number)
    {
        $units = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if ($number < 12) {
            return $units[$number];
        } elseif ($number < 20) {
            return $units[$number - 10] . " belas";
        } elseif ($number < 100) {
            return $units[(int)($number / 10)] . " puluh " . self::terbilang($number % 10);
        } elseif ($number < 200) {
            return "seratus " . self::terbilang($number - 100);
        } elseif ($number < 1000) {
            return $units[(int)($number / 100)] . " ratus " . self::terbilang($number % 100);
        } elseif ($number < 2000) {
            return "seribu " . self::terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return self::terbilang((int)($number / 1000)) . " ribu " . self::terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return self::terbilang((int)($number / 1000000)) . " juta " . self::terbilang($number % 1000000);
        } else {
            return "nomor terlalu besar";
        }
    }

    public static function ListPulsa($prefix)
    {
        $cases = [
            // Telkomsel
            '0852' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            '0853' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            '0811' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            '0812' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            '0813' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            '0821' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            '0822' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            '0851' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'Provider: Telkomsel',
            ],
            // Indonsat
            '0855' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'Provider: Indosat',
            ],
            '0856' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'Provider: Indosat',
            ],
            '0857' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'Provider: Indosat',
            ],
            '0858' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'Provider: Indosat',
            ],
            '0814' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'Provider: Indosat',
            ],
            '0815' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'Provider: Indosat',
            ],
            '0816' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'Provider: Indosat',
            ],
            // XL Axiata
            '0817' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'XL'],
                ],
                'message' => 'Provider: XL',
            ],
            '0818' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'XL'],
                ],
                'message' => 'Provider: XL',
            ],
            '0819' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'XL'],
                ],
                'message' => 'Provider: XL',
            ],
            '0859' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'XL'],
                ],
                'message' => 'Provider: XL',
            ],
            '0877' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'XL'],
                ],
                'message' => 'Provider: XL',
            ],
            '0878' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'XL'],
                ],
                'message' => 'Provider: XL',
            ],
            // Tri
            '0895' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TRI'],
                ],
                'message' => 'Provider: Tri',
            ],
            '0896' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TRI'],
                ],
                'message' => 'Provider: Tri',
            ],
            '0897' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TRI'],
                ],
                'message' => 'Provider: Tri',
            ],
            '0898' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TRI'],
                ],
                'message' => 'Provider: Tri',
            ],
            '0899' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'TRI'],
                ],
                'message' => 'Provider: Tri',
            ],
            // AXIS
            '0813' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'Provider: Axis',
            ],
            '0832' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'Provider: Axis',
            ],
            '0833' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'Provider: Axis',
            ],
            '0838' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'Provider: Axis',
            ],
            // Smartfren
            '0881' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0882' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0883' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0884' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0885' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0886' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0887' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0888' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
            '0889' => [
                'status' => true,
                'query' => [
                    ['category', 'Pulsa'],
                    // ['seller_product_status', 'Normal'],
                    ['type', 'Umum'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'Provider: Smartfren',
            ],
        ];

        if (array_key_exists($prefix, $cases)) {
            $case = $cases[$prefix];
            $status = $case['status'];
            $data = Prabayar::where($case['query'])
                ->orderByRaw('CAST(price AS DECIMAL(10, 2))')
                ->get();
            $message = $case['message'];
        } else {
            $status = false;
            $data = null;
            $message = 'Nomor Hp Tidak Dikenali';
        }

        return compact('status', 'data', 'message');
    }

    public static function getTypePrabayar($prefix)
    {
        $cases = [
            // Telkomsel
            '0852' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            '0853' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            '0811' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            '0812' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            '0813' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            '0821' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            '0822' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            '0851' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TELKOMSEL'],
                ],
                'message' => 'TELKOMSEL',
            ],
            // Indonsat
            '0855' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'INDOSAT',
            ],
            '0856' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'INDOSAT',
            ],
            '0857' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'INDOSAT',
            ],
            '0858' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'INDOSAT',
            ],
            '0814' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'INDOSAT',
            ],
            '0815' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'INDOSAT',
            ],
            '0816' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'INDOSAT'],
                ],
                'message' => 'INDOSAT',
            ],
            // XL Axiata
            '0817' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'XL'],
                ],
                'message' => 'XL',
            ],
            '0818' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'XL'],
                ],
                'message' => 'XL',
            ],
            '0819' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'XL'],
                ],
                'message' => 'XL',
            ],
            '0859' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'XL'],
                ],
                'message' => 'XL',
            ],
            '0877' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'XL'],
                ],
                'message' => 'XL',
            ],
            '0878' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'XL'],
                ],
                'message' => 'XL',
            ],
            // Tri
            '0895' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TRI'],
                ],
                'message' => 'TRI',
            ],
            '0896' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TRI'],
                ],
                'message' => 'TRI',
            ],
            '0897' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TRI'],
                ],
                'message' => 'TRI',
            ],
            '0898' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TRI'],
                ],
                'message' => 'TRI',
            ],
            '0899' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'TRI'],
                ],
                'message' => 'TRI',
            ],
            // AXIS
            '0813' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'AXIS',
            ],
            '0832' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'AXIS',
            ],
            '0833' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'AXIS',
            ],
            '0838' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'AXIS'],
                ],
                'message' => 'AXIS',
            ],
            // Smartfren
            '0881' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0882' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0883' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0884' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0885' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0886' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0887' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0888' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
            '0889' => [
                'status' => true,
                'query' => [
                    ['category', 'Data'],
                    ['brand', 'SMARTFREN'],
                ],
                'message' => 'SMARTFREN',
            ],
        ];

        if (array_key_exists($prefix, $cases)) {
            $case = $cases[$prefix];
            $status = $case['status'];
            $data = Prabayar::select('type')
                ->where($case['query'])
                ->orderByRaw('CAST(price AS DECIMAL(10, 2))')
                ->pluck('type')
                ->unique();
            $data->toArray();
            $message = $case['message'];
        } else {
            $status = false;
            $data = null;
            $message = 'Nomor Hp Tidak Dikenali';
        }

        return compact('status', 'data', 'message');
    }

    public static function getType($category, $brand)
    {
        $data = Prabayar::select('type')
            ->where(['category' => $category, 'brand' => $brand])
            ->orderByRaw('CAST(price AS DECIMAL(10, 2))')
            ->pluck('type')
            ->unique();
        $data->toArray();

        if ($data) {
            return [
                'status'    => true,
                'message'   => $brand,
                'data'      => $data,
            ];
        }

        return [
            'status'    => false,
            'message'   => 'Tidak Diketahui',
            'data'      => null,
        ];
    }

    public static function getEmoneyServices($type, $brand)
    {
        $data = Prabayar::where([
            'category' => 'E-Money',
            'brand' => $brand,
            'type' => $type
        ])
            ->orderByRaw('CAST(price AS DECIMAL(10, 2))')
            ->get();

        if ($data) {
            return [
                'status'    => true,
                'message'   => $brand,
                'data'      => $data,
            ];
        }

        return [
            'status'    => false,
            'message'   => 'Tidak Diketahui',
            'data'      => null,
        ];
    }

    public static function getWaUrl()
    {
        return env('WA_URL');
    }

    public static function formatPhoneNumber($number)
    {
        // Menghilangkan spasi
        $number = str_replace(' ', '', $number);

        // Menghilangkan tanda +
        if (substr($number, 0, 1) === '+') {
            $number = substr($number, 1);
        }

        // Mengganti awalan 0 dengan 62
        if (substr($number, 0, 1) === '0') {
            return '62' . substr($number, 1);
        }

        return $number;
    }
}
