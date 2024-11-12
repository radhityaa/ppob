<?php

namespace App\Http\Controllers;

use App\Helpers\TripayHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PaymentMethodRequest;
use Illuminate\Support\Facades\Http;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:admin'])->except(['list', 'detailMethod']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Payment Method';

        if ($request->ajax()) {
            $data = PaymentMethod::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d');
                })
                ->editColumn('fee', function ($row) {
                    return number_format($row->fee, 0, ',', '.');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Non-Aktif</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';
                    if ($row->provider === 'manual') {
                        $actionBtn = '<button type="button" id="edit" data-slug="' . $row->slug . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-pencil"></i></button>';
                    }
                    $actionBtn .= '<button type="button" id="delete" data-slug="' . $row->slug . '" class="deleteUser btn btn-danger btn-sm"><i class="ti ti-trash"></i></button>';

                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('payment-method.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getPaymentProvider($provider)
    {
        if ($provider === 'tripay') {
            $result = TripayHelper::getChannels();

            if (isset($result['success']) && $result['success'] === true) {
                if (isset($result['data']) && is_array($result['data'])) {
                    foreach ($result['data'] as $channel) {
                        PaymentMethod::updateOrCreate(
                            ['code' => $channel['code']],
                            [
                                'name' => $channel['name'],
                                'slug' => Str::slug($channel['name'] . "-" . Str::random(6)),
                                'group' => $channel['group'],
                                'code' => $channel['code'],
                                'fee' => $channel['total_fee']['flat'],
                                'percent_fee' => $channel['total_fee']['percent'],
                                'icon_url' => $channel['icon_url'],
                                'status' => $channel['active'],
                                'provider' => 'tripay'
                            ]
                        );
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengambil data.',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => isset($result['message']) ? $result['message'] : 'Terjadi kesalahan saat mengambil data dari Tripay.',
                ], 400); // Menggunakan status 400 (Bad Request) untuk menandakan kesalahan
            }
        }
    }

    public function deletePaymentProvider($provider)
    {
        PaymentMethod::where('provider', $provider)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus semua.',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentMethodRequest $request)
    {
        DB::beginTransaction();

        try {
            if (!$request->percent_fee) {
                $request['percent_fee'] = 0.0;
            }

            PaymentMethod::create([
                'name' => $name = $request->name,
                'slug' => Str::slug($name) . '-' . Str::random(6),
                'group' => 'manual',
                'code' => $request->code,
                'name' => $request->name,
                'fee' => $request->fee,
                'percent_fee' => $request->percent_fee,
                'status' => $request->status,
                'provider' => 'manual'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $data = PaymentMethod::where('slug', $slug)->first();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function list($type)
    {
        if ($type === 'virtual-account') {
            $data = PaymentMethod::where(['status' => 1, 'group' => 'Virtual Account'])->get();
            return response()->json($data);
        } else if ($type === 'retail') {
            $data = PaymentMethod::where(['status' => 1, 'group' => 'Convenience Store'])->get();
            return response()->json($data);
        } else if ($type === 'e-wallet') {
            $data = PaymentMethod::where(['status' => 1, 'group' => 'E-Wallet'])->get();
            return response()->json($data);
        } else {
            $data = PaymentMethod::where(['status' => 1, 'provider' => 'manual'])->get();
            return response()->json($data);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $data = PaymentMethod::where('slug', $slug)->first();
        $data->update([
            'name' => $name = $request->name,
            'slug' => Str::slug($name . "-" . Str::random(6)),
            'group' => $request->group,
            'code' => $request->code,
            'name' => $request->name,
            'fee' => $request->fee,
            'percent_fee' => $request->percent_fee,
            'status' => $request->status,
            'provider' => $request->provider
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diubah.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
        $data = PaymentMethod::where('slug', $slug)->first();
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.',
        ], 200);
    }

    public function detailMethod($code)
    {
        $data = PaymentMethod::where('code', $code)->first();
        return response()->json($data);
    }
}
