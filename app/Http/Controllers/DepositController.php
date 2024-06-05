<?php

namespace App\Http\Controllers;

use App\Helpers\TripayHelper;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Deposit';

        return view('deposit.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $deposit = Deposit::where(['user_id' => Auth::user()->id, 'status' => 'unpaid'])->first();

        if ($deposit) {
            return view('deposit.show', compact('deposit'));
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

        $orderItems = [
            [
                'name'        => 'Deposit Saldo',
                'price'       => $request->nominal,
                'quantity'    => 1,
            ]
        ];

        $response = TripayHelper::createDepositLocal($request->nominal, $request->method, $orderItems);

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
            'status' => $response->data->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Topup Saldo Berhasil',
            'invoice' =>  $response->data->merchant_ref
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        $title = 'Invoice : ' . $deposit->invoice;

        return view('deposit.show', compact('deposit', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
