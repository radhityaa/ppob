<?php

namespace App\Http\Controllers;

use App\Helpers\TripayHelper;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:admin')->except(['index', 'create', 'show', 'store', 'cancel']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('admin')) {
                $data = Deposit::latest()->get();
            } else {
                $data = Deposit::where('user_id', Auth::user()->id)->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->editColumn('nominal', function ($row) {
                    return 'Rp. ' . number_format($row->nominal, 0, ',', '.');
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'paid') {
                        return '<span class="badge bg-success">Paid</span>';
                    } else if ($row->status == 'unpaid') {
                        return '<span class="badge bg-danger">Unpaid</span>';
                    } else {
                        return '<span class="badge bg-warning">Cancel</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('deposit.show', $row->invoice) . '" class="btn btn-warning btn-sm me-1"><i class="ti ti-eye"></i></a>';

                    return '<div class="d-flex">' . $actionBtn . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $title = 'Deposit';
        // Cek apakah user memiliki peran admin
        if (Auth::user()->hasRole('admin')) {
            // Jika admin, ambil semua deposit
            $statusCounts = Deposit::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $totalNominal = Deposit::where('status', 'paid')->sum('nominal');
        } else {
            // Jika bukan admin, ambil deposit berdasarkan user_id
            $statusCounts = Deposit::where('user_id', Auth::user()->id)
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status');

            $totalNominal = Deposit::where('user_id', Auth::user()->id)
                ->where('status', 'paid')
                ->sum('nominal');
        }

        $totalPaid = $statusCounts->get('paid', 0);
        $totalUnpaid = $statusCounts->get('unpaid', 0);
        $totalCancel = $statusCounts->get('cancel', 0);

        return view('deposit.index', compact('title', 'totalPaid', 'totalUnpaid', 'totalCancel', 'totalNominal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $deposit = Deposit::where(['user_id' => Auth::user()->id, 'status' => 'unpaid'])->first();
        // dd($deposit);
        if ($deposit) {
            return redirect(route('deposit.show', $deposit->invoice));
        }

        $title = 'Deposit Saldo';

        return view('deposit.craete', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric'
        ]);

        $paymentMethod = PaymentMethod::where('code', $request->method)->first();

        if ($paymentMethod->group === 'manual') {
            $invoice = invoice(Auth::user()->id, 'DPSM');

            $result = Deposit::create([
                'user_id' => Auth::user()->id,
                'invoice' => $invoice,
                'method' => $request->method,
                'nominal' => $request->nominal,
                'fee' => $paymentMethod->fee,
                'total' => $request->nominal + $paymentMethod->fee,
                'amount_received' => $request->nominal,
                'status' => 'unpaid',
                'expired_at' => (time() + (24 * 60 * 60)), // 24 jam
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Topup Saldo Berhasil',
                'invoice' =>  $result->invoice
            ]);
        }

        $orderItems = [
            [
                'name'        => 'Deposit Saldo',
                'price'       => $request->nominal,
                'quantity'    => 1,
            ]
        ];

        $response = TripayHelper::createDepositLocal($request->nominal, $request->method, $orderItems);
        if (!$response->success) {
            return response()->json([
                'success' => true,
                'message' => $response->message,
            ], 400);
        }
        $expired_time = Carbon::createFromTimestamp($response->data->expired_time)->toDateTimeString();

        Deposit::create([
            'user_id' => Auth::user()->id,
            'invoice' => $response->data->merchant_ref,
            'method' => $response->data->payment_name,
            'nominal' => $request->nominal,
            'fee' => $response->data->total_fee,
            'total' => $response->data->amount,
            'amount_received' => $response->data->amount_received,
            'pay_code' => $response->data->pay_code,
            'pay_url' => $response->data->pay_url,
            'checkout_url' => $response->data->checkout_url,
            'status' => $response->data->status,
            'expired_at' => $expired_time
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Topup Saldo Berhasil',
            'invoice' =>  $response->data->merchant_ref
        ]);
    }

    public function cancel(Deposit $deposit)
    {
        if ($deposit->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Deposit Dengan Status Paid Tidak Bisa Dibatalkan!',
            ], 400);
        }

        $deposit->update(['status' => 'cancel']);

        return response()->json([
            'success' => true,
            'message' => 'Deposit Berhasil Dibatalkan',
        ]);
    }

    public function confirm(Deposit $deposit)
    {
        $deposit->update(['status' => 'paid']);
        $user = User::where('id', $deposit->user_id)->first();
        $user->update(['saldo' => DB::raw('saldo + ' . $deposit->nominal)]);

        return response()->json([
            'success' => true,
            'message' => 'Deposit Berhasil Dikonfirmasi',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        $title = 'Invoice : ' . $deposit->invoice;
        $terbilang = TripayHelper::terbilang($deposit->total);
        $paymentMethod = PaymentMethod::where('code', $deposit->method)->first();
        $depositType = $paymentMethod?->group;

        return view('deposit.show', compact('deposit', 'title', 'terbilang', 'depositType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit)
    {
        return response()->json($deposit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deposit $deposit)
    {
        dd($deposit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
