<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use App\Models\Pascabayar;
use App\Models\Prabayar;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
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

    public function pascabayar(Request $request)
    {
        $product = Pascabayar::where('buyer_sku_code', $request->buyerSkuCode)->first();
        $mode = DigiflazzHelper::getMode();
        $username = DigiflazzHelper::getUsername();
        $invoice = invoice(Auth::user()->id, 'PSC', 'transactions');
        $sign = DigiflazzHelper::getSign($invoice);
        $user = User::find(Auth::user()->id);
        $latestBalance = $user->saldo;
        $type = $request->type;

        $priceAdmin = $product->admin;
        $priceMember = $product->admin_member;
        $priceReseller = $product->admin_reseller;
        $priceAgen = $product->admin_agen;

        if (Auth::user()->hasRole('admin')) {
            $priceSell = $priceAdmin;
        } else if (Auth::user()->hasRole('member')) {
            $priceSell = $priceMember;
        } else if (Auth::user()->hasRole('reseller')) {
            $priceSell = $priceReseller;
        } else if (Auth::user()->hasRole('agen')) {
            $priceSell = $priceAgen;
        }

        if ($type === 'bill') {
            $commands = 'inq-pasca';
        } else {
            $commands = 'pay-pasca';
        }

        if ($mode === 'dev') {
            $data = [
                'commands' => $commands,
                'username' => $username,
                'buyer_sku_code' => 'pln',
                'customer_no' => '530000000002',
                'ref_id' => $invoice,
                'sign' => $sign,
                'testing' => true,
            ];
        } else if ($mode === 'prod') {
            $data = [
                'commands' => $commands,
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

        if ($type === 'bill') {
            $result = DigiflazzHelper::transaction('transaction', $data);
            return response()->json([
                'success' => true,
                'data' => $result->data
            ]);
        }

        if ($user->saldo < $priceSell) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak mencukupi'
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
            'admin' => $priceSell,
            'price' => $priceSell,
            'selling_price' => $priceSell,
            'customer_no' => $result->data->customer_no,
            'customer_name' => $result->data->customer_name,
            'tarif' => $result->data->desc->tarif,
            'daya' => $result->data->desc->daya,
            'detail' => json_encode($result->data->desc->detail),
            'message' => $result->data->message,
            'sn' => $result->data->sn,
            'status' => $result->data->status,
            'type' => 'pascabayar'
        ]);

        $user->update([
            'saldo' => $user->saldo - $priceSell
        ]);

        createMutation($user->id, 'Debet', 'Pembelian ' . $product->product_name . '.', $priceSell, $latestBalance, $user->saldo, $invoice);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan pemesanan.',
            'data' => $result->data
        ]);
    }
}
