<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use App\Helpers\WhatsappHelper;
use App\Models\Prabayar;
use App\Models\SettingProfit;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Prabayar::where('buyer_sku_code', $request->buyerSkuCode)->first();
        $mode = DigiflazzHelper::getMode();
        $username = DigiflazzHelper::getUsername();
        $invoice = invoice(Auth::user()->id, 'TRX', 'transactions');
        $sign = DigiflazzHelper::getSign($invoice);
        $user = User::find(Auth::user()->id);
        $latestBalance = $user->saldo;

        $priceAdmin = $product->price;
        $priceMember = $product->price_member;
        $priceReseller = $product->price_reseller;
        $priceAgen = $product->price_agen;

        if (Auth::user()->hasRole('admin')) {
            $priceSell = $priceAdmin;
        } else if (Auth::user()->hasRole('member')) {
            $priceSell = $priceMember;
        } else if (Auth::user()->hasRole('reseller')) {
            $priceSell = $priceReseller;
        } else if (Auth::user()->hasRole('agen')) {
            $priceSell = $priceAgen;
        }

        if ($user->saldo < $priceSell) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi'
            ], 400);
        }

        if ($mode === 'dev') {
            $data = [
                'username' => $username,
                'buyer_sku_code' => 'xld10',
                'customer_no' => '087800001233',
                'ref_id' => $invoice,
                'sign' => $sign,
                'testing' => true,
            ];
        } else if ($mode === 'prod') {
            $data = [
                'username' => $username,
                'buyer_sku_code' => $request->buyerSkuCode,
                'customer_no' => $request->target,
                'ref_id' => $invoice,
                'sign' => $sign
            ];
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan transaksi.',
            ], 400);
        }

        $result = DigiflazzHelper::transaction('transaction', $data);

        if (config('app.trx_type') !== 'dev') {
            if (isset($result->data)) {
                if (isset($result->data->rc) && $result->data->rc !== "00" && $result->data->rc !== "03") {
                    return response()->json([
                        'success' => false,
                        'message' => $result->data->message ?? "Terjadi kesalahan saat melakukan transaksi.",
                    ], 400);
                }
            }
        }

        $trx = Transaction::create([
            'user_id' => Auth::user()->id,
            'invoice' => $invoice,
            'target' => $request->target,
            'buyer_sku_code' => $request->buyerSkuCode,
            'product_name' => $product->product_name,
            'price' => $priceSell,
            'message' => $result->data->message,
            'sn' => $result->data->sn,
            'status' => $result->data->status,
        ]);

        $user->update([
            'saldo' => $user->saldo - $priceSell
        ]);

        createMutation($user->id, 'Debet', 'Pembelian ' . $product->product_name . '.', $priceSell, $latestBalance, $user->saldo, $invoice);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan pemesanan.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
