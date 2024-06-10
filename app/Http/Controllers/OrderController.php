<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function pulsa(Request $request)
    {
        if ($request->ajax()) {
            $target = $request->target;
            $prefix = substr($target, 0, 4);

            $result = MyHelper::ListPulsa($prefix);

            return response()->json([
                'status' => $result['status'],
                'message' => $result['message'],
                'data' => $result['data'],
            ]);
        }

        $title = 'Isi Ulang Pulsa';

        return view('orders.prabayar.pulsa', compact('title'));
    }
}
