<?php

namespace App\Helpers;

use App\Models\Prabayar;

class MyHelper
{
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

    public static function CategoryKuota($prefix)
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
}
