<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use App\Models\Pascabayar;
use App\Models\SettingMargin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PascabayarController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin'])->only(['getServicesDigiflazz', 'show']);
    }

    public function index(Request $request)
    {
        $title = 'Daftar Harga Pascabayar';
        $role = Auth::user()->hasRole('admin');

        return view('products.pascabayar.index', compact('title', 'role'));
    }

    public function getServicesDigiflazz()
    {
        $result = DigiflazzHelper::getService('pasca');

        if (isset($result->data)) {
            // Jika respons mengandung kesalahan
            if (isset($result->data->rc) && $result->data->rc !== "00") {
                return response()->json([
                    'success' => false,
                    'message' => $result->data->message ?? 'Terjadi kesalahan saat mengambil data dari Digiflazz.',
                ], 400);
            }

            // Jika respons berhasil
            if (is_array($result->data)) {
                $digiflazzSkuCodes = [];

                foreach ($result->data as $item) {
                    $digiflazzSkuCodes[] = $item->buyer_sku_code;

                    Pascabayar::updateOrCreate(
                        ['buyer_sku_code' => $item->buyer_sku_code],
                        [
                            "product_name" => $item->product_name,
                            "category" => $item->category,
                            "brand" => $item->brand,
                            "seller_name" => $item->seller_name,
                            "admin" => $item->admin,
                            "commission" => $item->commission,
                            "buyer_sku_code" => $item->buyer_sku_code,
                            "buyer_product_status" => $item->buyer_product_status,
                            "seller_product_status" => $item->seller_product_status,
                            "description" => $item->desc,
                        ]
                    );
                }

                Pascabayar::whereNotIn('buyer_sku_code', $digiflazzSkuCodes)->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengambil data.',
                ], 200);
            }
        } else {
            // Jika respons tidak memiliki data sama sekali
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data dari Digiflazz.',
            ], 400);
        }
    }

    public function getProduct(Request $request)
    {
        if (Auth::user()->hasRole('admin')) {
            $data = Pascabayar::where('brand', $request->service)->orderBy('admin', 'asc')->get();
        } else if (Auth::user()->hasRole('reseller')) {
            $data = Pascabayar::where('brand', $request->service)->orderBy('admin_reseller', 'asc')->get();
        } else if (Auth::user()->hasRole('agen')) {
            $data = Pascabayar::where('brand', $request->service)->orderBy('admin_agen', 'asc')->get();
        } else if (Auth::user()->hasRole('member')) {
            $data = Pascabayar::where('brand', $request->service)->orderBy('admin_member', 'asc')->get();
        }

        $result = [
            'data' => $data,
            'role' => Auth::user()->getRoleNames()[0],
        ];

        return response()->json($result);
    }

    public function deleteAllServices()
    {
        Pascabayar::truncate();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus semua.',
        ], 200);
    }
}
