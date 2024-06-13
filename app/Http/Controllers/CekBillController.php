<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use Illuminate\Http\Request;

class CekBillController extends Controller
{
    public function token(Request $request)
    {
        $target = $request->target;

        $result = DigiflazzHelper::validasiTokenPln($target);
        $data = $result->data;

        if ($data->name === '' || $data->segment_power === '') {
            return response()->json([
                'success' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $result->data
        ]);
    }
}
