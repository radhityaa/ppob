<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Helpers\TripayHelper;
use App\Helpers\WhatsappHelper;
use App\Models\Deposit;
use App\Models\Mutation;
use App\Models\PaymentMethod;
use App\Models\Settings;
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
        $this->middleware(['role:admin'])->except(['index', 'create', 'show', 'store', 'cancel']);
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
                        return '<span class="badge bg-dark">Unpaid</span>';
                    } else {
                        return '<span class="badge bg-danger">Failed</span>';
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
        $totalFailed = $statusCounts->get('failed', 0);

        return view('deposit.index', compact('title', 'totalPaid', 'totalUnpaid', 'totalFailed', 'totalNominal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $deposit = Deposit::where(['user_id' => Auth::user()->id, 'status' => 'unpaid'])->first();
        $settings = Settings::where('slug', 'settings-information-deposit')->first();

        if ($deposit) {
            return redirect(route('deposit.show', $deposit->invoice));
        }

        $title = 'Deposit Saldo';

        return view('deposit.craete', compact('title', 'settings'));
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

        // Perhitungan fee berdasarkan flat_fee dan percent_fee
        $flatFee = $paymentMethod->fee;
        $percentFee = $paymentMethod->percent_fee;
        $fee = $flatFee;

        if ($percentFee > 0) {
            $fee = ($request->nominal * ($percentFee / 100) + $flatFee);
        }

        if ($paymentMethod->group === 'manual') {
            $invoice = invoice(Auth::user()->id, 'DPSM');

            $result = Deposit::create([
                'user_id' => Auth::user()->id,
                'invoice' => $invoice,
                'method' => $request->method,
                'nominal' => $request->nominal,
                'fee' => $fee,
                'total' => $request->nominal + $paymentMethod->fee,
                'amount_received' => $request->nominal,
                'status' => 'unpaid',
                'expired_at' => (time() + (24 * 60 * 60)), // 24 jam
            ]);

            $data = [
                'app_name' => env('APP_NAME'),
                'name' => Auth::user()->name,
                'username' => Auth::user()->username,
                'phone' => Auth::user()->phone,
                'email' => Auth::user()->email,
                'shop_name' => Auth::user()->shop_name,
                'address' => Auth::user()->address,
                'saldo' => 'Rp' . number_format(Auth::user()->saldo, 0, '.', '.'),
                'invoice' => $result->invoice,
                'method' => $result->method,
                'pay_code' => $result->pay_code,
                'pay_url' => $result->pay_url,
                'checkout_url' => $result->checkout_url,
                'nominal' => 'Rp' . number_format($result->nominal, 0, '.', '.'),
                'total' => 'Rp' . number_format($result->total, 0, '.', '.'),
                'fee' => 'Rp' . number_format($result->fee, 0, '.', '.'),
                'amount_received' => 'Rp' . number_format($result->amount_received, 0, '.', '.'),
                'status' => $result->status,
                'paid_at' => $result->paid_at?->format('Y-m-d H:i:s'),
                'expired_at' => $result->expired_at?->format('Y-m-d H:i:s'),
                'created_at' => $result->created_at?->format('Y-m-d H:i:s'),
                'url' => route('deposit.show', $result->invoice),
            ];

            if (WhatsappHelper::getStatus()) {
                WhatsappHelper::sendMessage('deposit-manual-user', $data, Auth::user()->phone);
                WhatsappHelper::sendMessage('deposit-manual-admin', $data, env('WA_ADMIN_NUMBER'));
            }

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

        $result = Deposit::create([
            'user_id' => Auth::user()->id,
            'invoice' => $response->data->merchant_ref,
            'method' => $response->data->payment_name,
            'nominal' => $request->nominal,
            'fee' => $fee,
            'total' => $response->data->amount,
            'amount_received' => $response->data->amount_received,
            'pay_code' => $response->data->pay_code,
            'pay_url' => $response->data->pay_url,
            'checkout_url' => $response->data->checkout_url,
            'status' => $response->data->status,
            'expired_at' => $expired_time
        ]);

        $data = [
            'app_name' => env('APP_NAME'),
            'name' => Auth::user()->name,
            'username' => Auth::user()->username,
            'phone' => Auth::user()->phone,
            'email' => Auth::user()->email,
            'shop_name' => Auth::user()->shop_name,
            'address' => Auth::user()->address,
            'saldo' => 'Rp' . number_format(Auth::user()->saldo, 0, '.', '.'),
            'invoice' => $result->invoice,
            'method' => $result->method,
            'pay_code' => $result->pay_code,
            'pay_url' => $result->pay_url,
            'checkout_url' => $result->checkout_url,
            'nominal' => 'Rp' . number_format($result->nominal, 0, '.', '.'),
            'total' => 'Rp' . number_format($result->total, 0, '.', '.'),
            'fee' => 'Rp' . number_format($result->fee, 0, '.', '.'),
            'amount_received' => 'Rp' . number_format($result->amount_received, 0, '.', '.'),
            'status' => $result->status,
            'paid_at' => $result->paid_at?->format('Y-m-d H:i:s'),
            'expired_at' => $result->expired_at?->format('Y-m-d H:i:s'),
            'created_at' => $result->created_at?->format('Y-m-d H:i:s'),
            'url' => route('deposit.show', $result->invoice),
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('deposit-otomatic-user', $data, Auth::user()->phone);
        }

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

        $deposit->update(['status' => 'failed']);

        return response()->json([
            'success' => true,
            'message' => 'Deposit Berhasil Dibatalkan',
        ]);
    }

    public function confirm(Deposit $deposit)
    {
        $deposit->update(['status' => 'paid', 'paid_at' => now()]);
        $user = User::where('id', $deposit->user_id)->first();

        $latestBalance = $user->saldo;

        $user->update(['saldo' => $user->saldo + $deposit->nominal]);

        $data = [
            'app_name' => env('APP_NAME'),
            'name' => Auth::user()->name,
            'invoice' => $deposit->invoice,
            'method' => $deposit->method,
            'nominal' => 'Rp' . number_format($deposit->nominal, 0, '.', '.'),
            'fee' => 'Rp' . number_format($deposit->fee, 0, '.', '.'),
            'total' => 'Rp' . number_format($deposit->total, 0, '.', '.'),
            'amount_received' => 'Rp' . number_format($deposit->amount_received, 0, '.', '.'),
            'status' => $deposit->status,
            'paid_at' => $deposit->paid_at?->format('d-m-Y H:i:s'),
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('deposit-manual-notification-user', $data, Auth::user()->phone);
        }

        createMutation($deposit->user_id, 'Kredit', 'Deposit Melalui Manual', $deposit->nominal, $latestBalance, $user->saldo, $deposit->invoice);

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
        $terbilang = MyHelper::terbilang($deposit->total);
        $paymentMethod = PaymentMethod::where('code', $deposit->method)->first();
        $depositType = $paymentMethod?->group;

        return view('deposit.show', compact('deposit', 'title', 'terbilang', 'depositType'));
    }

    public function print(Request $request)
    {
        $deposit = Deposit::where('invoice', $request->invoice)->first();
        $terbilang = MyHelper::terbilang($deposit->total);
        return view('deposit.print', compact('deposit', 'terbilang'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
