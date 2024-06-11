<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use App\Models\Prabayar;
use App\Models\SettingMargin;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PrabayarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Prabayar::get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cut_off', function ($row) {
                    return $row->start_cut_off . $row->end_cut_off;
                })
                ->editColumn('buyer_product_status', function ($row) {
                    if ($row->buyer_product_status) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Non-Aktif</span>';
                    }
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, '', '.');
                })
                ->editColumn('action', function ($row) {
                    $actionBtn = '<button type="button" data-code="' . $row->buyer_sku_code . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-eye"></i></button>';
                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->rawColumns(['action', 'buyer_product_status'])
                ->make(true);
        }

        $title = 'Prabayar';
        return view('products.prabayar.index', compact('title'));
    }

    public function getServices()
    {
        $settingMargin = SettingMargin::first();
        $margin = $settingMargin->margin;

        $result = DigiflazzHelper::getService('prepaid');

        if (isset($result->data) && isset($result->data->rc) && $result->data->rc == "00" && is_array($result->data->items)) {
            foreach ($result->data->items as $item) {
                Prabayar::updateOrCreate(
                    ['buyer_sku_code' => $item->buyer_sku_code],
                    [
                        'product_name' => $item->product_name,
                        'category' => $item->category,
                        'brand' => $item->brand,
                        'type' => $item->type,
                        'price' => $item->price + $margin,
                        'seller_name' => $item->seller_name,
                        'buyer_sku_code' => $item->buyer_sku_code,
                        'buyer_product_status' => $item->buyer_product_status,
                        'seller_product_status' => $item->seller_product_status,
                        'unlimited_stock' => $item->unlimited_stock,
                        'stock' => $item->stock,
                        'multi' => $item->multi,
                        'start_cut_off' => $item->start_cut_off,
                        'end_cut_off' => $item->end_cut_off,
                        'desc' => $item->desc,
                        'provider' => 'digiflazz',
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => isset($result->data->message) ? $result->data->message : 'Terjadi kesalahan saat mengambil data dari Digiflazz.',
            ], 400);
        }
    }

    public function deleteAllServices()
    {
        Prabayar::truncate();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus semua.',
        ], 200);
    }
}
