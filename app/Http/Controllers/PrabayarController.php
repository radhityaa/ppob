<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use App\Helpers\WhatsappHelper;
use App\Http\Resources\ProductResource;
use App\Models\Prabayar;
use App\Models\SettingMargin;
use App\Models\Transaction;
use App\Models\WhatsappGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use Yajra\DataTables\DataTables;

class PrabayarController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin'])->only(['getServicesDigiflazz', 'show']);
    }

    public function index(Request $request)
    {
        // if ($request->ajax()) {
        //     $data = Prabayar::get();

        //     return DataTables::of($data)
        //         ->addIndexColumn()
        //         ->addColumn('cut_off', function ($row) {
        //             return $row->start_cut_off . $row->end_cut_off;
        //         })
        //         ->editColumn('seller_product_status', function ($row) {
        //             if ($row->seller_product_status) {
        //                 return '<span class="badge bg-success">Normal</span>';
        //             } else {
        //                 return '<span class="badge bg-danger">Gangguan</span>';
        //             }
        //         })
        //         ->editColumn('price', function ($row) {
        //             return 'Rp ' . number_format($row->price, 0, '', '.');
        //         })
        //         ->editColumn('action', function ($row) {
        //             if (Auth::user()->role('admin')) {
        //                 $actionBtn = '<button id="detailProduct" data-code="' . $row->buyer_sku_code . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-eye"></i></button>';
        //                 return '<div class="d-flex">' . $actionBtn . '</div>';
        //             }

        //             return '';
        //         })
        //         ->rawColumns(['action', 'seller_product_status'])
        //         ->make(true);
        // }

        $title = 'Daftar Harga Prabayar';
        $role = Auth::user()->hasRole('admin');

        return view('products.prabayar.index', compact('title', 'role'));
    }

    public function getProvider(Request $request)
    {
        $data = Prabayar::where('category', $request->service)->select('brand')->groupBy('brand')->get();
        return response()->json($data);
    }

    public function getType(Request $request)
    {
        $data = Prabayar::where('category', $request->service)->where('brand', $request->provider)->select('type')->groupBy('type')->get();
        return response()->json($data);
    }

    public function getServices(Request $request)
    {
        if (Auth::user()->hasRole('admin')) {
            $data = Prabayar::where('category', $request->service)->where('brand', $request->provider)->where('type', $request->category)->orderBy('price', 'asc')->get();
        } else if (Auth::user()->hasRole('reseller')) {
            $data = Prabayar::where('category', $request->service)->where('brand', $request->provider)->where('type', $request->category)->orderBy('price_reseller', 'asc')->get();
        } else if (Auth::user()->hasRole('agen')) {
            $data = Prabayar::where('category', $request->service)->where('brand', $request->provider)->where('type', $request->category)->orderBy('price_agen', 'asc')->get();
        } else if (Auth::user()->hasRole('member')) {
            $data = Prabayar::where('category', $request->service)->where('brand', $request->provider)->where('type', $request->category)->orderBy('price_member', 'asc')->get();
        }

        // $data['role'] = Auth::user()->getRoleNames()[0];
        $result = [
            'data' => $data,
            'role' => Auth::user()->getRoleNames()[0],
        ];

        return response()->json($result);
    }

    public function show($buyer_sku_code)
    {
        $data = Prabayar::where('buyer_sku_code', $buyer_sku_code)->first();
        return response()->json(new ProductResource($data));
    }

    public function getServicesDigiflazz()
    {
        $settingMarginMember = SettingMargin::where('slug', 'member')->first();
        $settingMarginReseller = SettingMargin::where('slug', 'reseller')->first();
        $settingMarginAgen = SettingMargin::where('slug', 'agen')->first();

        $marginMember = $settingMarginMember['margin'];
        $marginReseller = $settingMarginReseller['margin'];
        $marginAgen = $settingMarginAgen['margin'];

        $result = DigiflazzHelper::getService('prepaid');

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

                    Prabayar::updateOrCreate(
                        ['buyer_sku_code' => $item->buyer_sku_code],
                        [
                            'product_name' => $item->product_name,
                            'category' => $item->category,
                            'brand' => $item->brand,
                            'type' => $item->type,
                            'price' => $item->price,
                            'price_member' => $item->price + $marginMember,
                            'price_reseller' => $item->price + $marginReseller,
                            'price_agen' => $item->price + $marginAgen,
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

                Prabayar::whereNotIn('buyer_sku_code', $digiflazzSkuCodes)->delete();

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

    public function detailServices($id)
    {
        $data = Prabayar::find($id);
        $saldo = Auth::user()->saldo;
        $role = Auth::user()->getRoleNames()[0];

        return response()->json([
            'data' => $data,
            'saldo' => $saldo,
            'role' => $role,
        ]);
    }

    public function deleteAllServices()
    {
        Prabayar::truncate();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus semua.',
        ], 200);
    }

    public function history(Request $request)
    {
        $filterStatus = $request->status;
        $filterInvoice = $request->invoice;

        $title = 'Riwayat Pembelian';
        $query = Transaction::where('type', 'prabayar');

        if (Auth::user()->hasRole('admin')) {
            // Jika admin, ambil semua data
            $statusCounts = Transaction::select('status', DB::raw('count(*) as count'))
                ->where('type', 'prabayar')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $total = Transaction::where('type', 'prabayar')->count();
        } else {
            // Jika bukan admin, filter berdasarkan user_id
            $query = $query->where('user_id', Auth::user()->id);

            $statusCounts = Transaction::select('status', DB::raw('count(*) as count'))
                ->where('type', 'prabayar')
                ->where('user_id', Auth::user()->id)
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $total = Transaction::where('type', 'prabayar')->where('user_id', Auth::user()->id)->count();
        }

        // Filter berdasarkan status
        if ($filterStatus) {
            $query = $query->where('status', $filterStatus);
        }

        // Filter berdasarkan invoice
        if ($filterInvoice) {
            $query = $query->where('invoice', $filterInvoice);
        }

        // Ambil data yang difilter dan urutkan berdasarkan waktu terbaru
        $data = $query->latest()->paginate(6);
        $totalSukses = $statusCounts->get('Sukses', 0);
        $totalPending = $statusCounts->get('Pending', 0);
        $totalGagal = $statusCounts->get('Gagal', 0);
        $checkWaat = WhatsappGateway::where('user_id', Auth::user()->id)->first();
        $waStatus = WhatsappHelper::getStatus();

        return view('history.prabayar', compact('title', 'totalSukses', 'totalPending', 'totalGagal', 'total', 'checkWaat', 'data', 'filterStatus', 'filterInvoice', 'waStatus'));
    }

    public function sendInvoice(Request $request)
    {
        $data = Transaction::where('invoice', $request->invoice)->first();

        $dataNotif = [
            'shop_name' => Auth::user()->shop_name,
            'address' => Auth::user()->address,
            'created_at' => $data->created_at->format('d-m-Y H:i:s'),
            'invoice' => $data->invoice,
            'target' => $data->target,
            'price' => $request->price,
            'status' => $data->status,
            'sn' => $data->sn,
            'message' => $data->message,
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('transaction-notification-user', $dataNotif, $request->receiver);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengirim invoice ke Whastapp Pelanggan.',
            ]);
        }
    }

    public function historyDetail($invoice, Request $request)
    {
        $data = Transaction::where('invoice', $invoice)->first();

        return response()->json($data);
    }

    public function print(Request $request)
    {
        $dataOrder = Transaction::where('invoice', $request->invoice)->first();
        $priceSell = formatRupiahToNumber($request->margin);

        $seller = new Party([
            'name' => $dataOrder->user->name,
            'phone' => $dataOrder->user->phone,
            'email' => $dataOrder->user->email,
            'target' => $dataOrder->target,
            'sn' => $dataOrder->sn
        ]);

        $buyer = new Party([
            'name' => $dataOrder->target
        ]);

        $item = [
            (new InvoiceItem())
                ->title($dataOrder->product_name)
                ->description($dataOrder->message)
                ->pricePerUnit($priceSell)
        ];

        $invoice = Invoice::make($dataOrder->user->shop_name . ' ' . $dataOrder->invoice)->template('transaction')
            ->status($dataOrder->status)
            ->serialNumberFormat($dataOrder->invoice)
            ->seller($seller)
            ->buyer($buyer)
            ->date($dataOrder->created_at)
            ->dateFormat('d/m/Y h:i')
            ->currencyCode('idr')
            ->addItems($item)
            ->filename($dataOrder->user->shop_name . ' ' . $dataOrder->invoice);

        return $invoice->stream();
    }

    public function sendInvoicePrabayar(Request $request)
    {
        $dataOrder = Transaction::where('invoice', $request->invoice)->first();
        $priceSell = formatRupiahToNumber($request->margin);

        $data = [
            'app_name' => env('APP_NAME'),
            'name' => $dataOrder->user->name,
            'username' => $dataOrder->user->username,
            'phone' => $dataOrder->user->phone,
            'email' => $dataOrder->user->email,
            'shop_name' => $dataOrder->user->shop_name,
            'address' => $dataOrder->user->address,
            'saldo' => 'Rp' . number_format($dataOrder->user->saldo, 0, '.', '.'),
            'invoice' => $dataOrder->invoice,
            'target' => $dataOrder->target,
            'product_name' => $dataOrder->product_name,
            'price' => 'Rp' . number_format($priceSell, 0, '.', '.'),
            'customer_no' => $dataOrder->customer_no,
            'customer_name' => $dataOrder->customer_name,
            'admin' => $dataOrder->admin,
            'description' => $dataOrder->description,
            'message' => $dataOrder->message,
            'sn' => $dataOrder->sn,
            'selling_price' => $dataOrder->selling_price,
            'tarif' => $dataOrder->tarif,
            'daya' => $dataOrder->daya,
            'billing' => $dataOrder->billing,
            'detail' => $dataOrder->detail,
            'status' => $dataOrder->status,
            'type' => $dataOrder->type,
            'created_at' => $dataOrder->created_at?->format('Y-m-d H:i:s'),
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('transaction-notification-user', $data, $request->receiver);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengirim notifikasi ke Whatsapp Pelanggan.',
        ]);
    }
}
