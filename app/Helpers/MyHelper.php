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
}
