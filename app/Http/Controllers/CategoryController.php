<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function kuota(Request $request)
    {
        $target = $request->target;
        $prefix = substr($target, 0, 4);

        $result = MyHelper::getTypePrabayar($prefix);

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'data' => $result['data'],
        ]);
    }

    public function show(Request $request)
    {
        $data = MyHelper::getType($request->category, $request->brand);
        return response()->json($data);
    }
}
