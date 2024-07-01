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
        $this->middleware(['role:admin'])->only(['getServices', 'show']);
    }

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
                        return '<span class="badge bg-success">Normal</span>';
                    } else {
                        return '<span class="badge bg-danger">Gangguan</span>';
                    }
                })
                ->editColumn('price', function ($row) {
                    return 'Rp ' . number_format($row->price, 0, '', '.');
                })
                ->editColumn('action', function ($row) {
                    if (Auth::user()->role('admin')) {
                        $actionBtn = '<button id="detailProduct" data-code="' . $row->buyer_sku_code . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-eye"></i></button>';
                        return '<div class="d-flex">' . $actionBtn . '</div>';
                    }

                    return '';
                })
                ->rawColumns(['action', 'buyer_product_status'])
                ->make(true);
        }

        $title = 'Prabayar';
        $role = Auth::user()->hasRole('admin');

        return view('products.prabayar.index', compact('title', 'role'));
    }

    public function show($buyer_sku_code)
    {
        $data = Prabayar::where('buyer_sku_code', $buyer_sku_code)->first();
        return response()->json(new ProductResource($data));
    }

    public function getServices()
    {
        $settingMargin = SettingMargin::first();
        $margin = $settingMargin->margin;

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
                foreach ($result->data as $item) {
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
        return response()->json([
            'data' => $data,
            'saldo' => $saldo
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
        if ($request->ajax()) {
            if (Auth::user()->hasRole('admin')) {
                $data = Transaction::where('type', 'prabayar')->latest()->get();
            } else {
                $data = Transaction::where('user_id', Auth::user()->id)->where('type', 'prabayar')->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('sn', function ($row) {
                    return $row->sn ? '<span class="copy-sn" data-sn="' . $row->sn . '">' . $row->sn . ' <span class="copy-text">(copy)</span></span>' : '';
                })
                ->editColumn('status', function ($row) {
                    switch ($row->status) {
                        case 'Sukses':
                            return '<span class="badge bg-success">Sukses</span>';
                            break;
                        case 'Pending':
                            return '<span class="badge bg-warning">Pending</span>';
                            break;
                        case 'Gagal':
                            return '<span class="badge bg-danger">Gagal</span>';
                            break;
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<button data-invoice="' . $row->invoice . '" id="detail" class="btn btn-info btn-sm me-1"><i class="ti ti-eye"></i></button>';
                    $actionBtn .= '<button id="share" data-invoice="' . $row->invoice . '" data-target="' . $row->target . '" class="btn btn-success btn-sm me-1"><i class="ti ti-share"></i></button>';

                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->rawColumns(['action', 'status', 'sn'])
                ->make(true);
        }

        $title = 'Riwayat Pembelian';

        if (Auth::user()->hasRole('admin')) {
            // Jika admin, ambil semua deposit
            $statusCounts = Transaction::where('type', 'prabayar')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $total = Transaction::where('type', 'prabayar')->count();
        } else {
            // Jika bukan admin, ambil transaction berdasarkan user_id
            $statusCounts = Transaction::where('user_id', Auth::user()->id)
                ->where('type', 'prabayar')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $total = Transaction::where('user_id', Auth::user()->id)->where('type', 'prabayar')->count();
        }

        $totalSukses = $statusCounts->get('Sukses', 0);
        $totalPending = $statusCounts->get('Pending', 0);
        $totalGagal = $statusCounts->get('Gagal', 0);
        $checkWaat = WhatsappGateway::where('user_id', Auth::user()->id)->first();

        return view('history.prabayar', compact('title', 'totalSukses', 'totalPending', 'totalGagal', 'total', 'checkWaat'));
    }

    public function historyDetail($invoice)
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

        $invoice = Invoice::make('Ayasya Shop')->template('transaction')
            ->status($dataOrder->status)
            ->serialNumberFormat($dataOrder->invoice)
            ->seller($seller)
            ->buyer($buyer)
            ->date($dataOrder->created_at)
            ->dateFormat('d/m/Y h:i')
            ->currencyCode('idr')
            ->addItems($item);

        return $invoice->stream();
    }

    public function wa(Request $request)
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
            'url' => route('deposit.show', $dataOrder->invoice),
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('transaction-notification-user', $data, $request->phone);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengirim notifikasi ke Whatsapp.',
        ]);
    }
}
