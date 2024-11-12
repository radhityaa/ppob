<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Models\Prabayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function pulsa(Request $request)
    {
        if ($request->ajax()) {
            $target = $request->target;
            $prefix = substr($target, 0, 4);

            $result = MyHelper::ListPulsa($prefix);
            $role = Auth::user()->getRoleNames()[0];

            return response()->json([
                'status' => $result['status'],
                'message' => $result['message'],
                'data' => $result['data'],
                'role' => $role,
            ]);
        }

        $title = 'Isi Ulang Pulsa';

        return view('orders.prabayar.pulsa', compact('title'));
    }

    public function kuota(Request $request)
    {
        if ($request->ajax()) {
            $data = Prabayar::where([
                'category' => 'Data',
                'brand' => $request->brand,
                'type' => $request->type
            ])
                ->orderByRaw('CAST(price AS DECIMAL(10, 2))')
                ->get();

            return response()->json($data);
        }

        $title = 'Isi Ulang Kuota';

        return view('orders.prabayar.kuota', compact('title'));
    }

    public function token(Request $request)
    {
        if ($request->ajax()) {
            $data = Prabayar::where('category', 'PLN')->orderByRaw('CAST(price AS DECIMAL(10, 2))')->get();

            if (!$data) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Tidak Ditemukan',
                    'data'      => null,
                ], 400);
            }

            return response()->json([
                'status'    => true,
                'message'   => 'Token PLN',
                'data'      => $data,
            ]);
        }

        $title = 'Token PLN';

        return view('orders.prabayar.token', compact('title'));
    }

    public function dana(Request $request)
    {
        if ($request->ajax()) {
            $data = MyHelper::getEmoneyServices($request->type, 'Dana');
            return response()->json($data['data']);
        }

        $title = 'Isi Ulang Dana';

        return view('orders.prabayar.emoney.dana', compact('title'));
    }

    public function ovo(Request $request)
    {
        if ($request->ajax()) {
            $data = MyHelper::getEmoneyServices($request->type, 'OVO');
            return response()->json($data['data']);
        }

        $title = 'Isi Ulang OVO';

        return view('orders.prabayar.emoney.ovo', compact('title'));
    }

    public function grab(Request $request)
    {
        if ($request->ajax()) {
            $data = MyHelper::getEmoneyServices($request->type, 'Grab');
            return response()->json($data['data']);
        }

        $title = 'Isi Ulang Grab';

        return view('orders.prabayar.emoney.grab', compact('title'));
    }

    public function gopay(Request $request)
    {
        if ($request->ajax()) {
            $data = MyHelper::getEmoneyServices($request->type, 'GO PAY');
            return response()->json($data['data']);
        }

        $title = 'Isi Ulang Gopay';

        return view('orders.prabayar.emoney.gopay', compact('title'));
    }
}
