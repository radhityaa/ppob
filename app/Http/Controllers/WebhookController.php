<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use App\Helpers\TripayHelper;
use App\Helpers\WhatsappHelper;
use App\Models\Deposit;
use App\Models\Prabayar;
use App\Models\Profit;
use App\Models\SettingProfit;
use App\Models\SettingProvider;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Ramsey\Uuid\Uuid;

class WebhookController extends Controller
{
    public function callbackTripay(Request $request)
    {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, TripayHelper::getPrivateKey());

        if ($signature !== (string) $callbackSignature) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid signature',
            ]);
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return Response::json([
                'success' => false,
                'message' => 'Unrecognized callback event, no action was taken',
            ]);
        }

        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid data sent by tripay',
            ]);
        }

        $depositInvoice = $data->merchant_ref;
        $status = strtoupper((string) $data->status);

        if ($data->is_closed_payment === 1) {
            $deposit = Deposit::where('invoice', $depositInvoice)->where('status', '=', 'unpaid')->first();

            if (!$deposit) {
                return Response::json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $depositInvoice,
                ]);
            }

            switch ($status) {
                case 'PAID':
                    $deposit->update([
                        'status' => 'paid',
                        'paid_at' => Carbon::createFromTimestamp($data->paid_at)->toDateTimeString()
                    ]);
                    $user = User::where('id', $deposit->user_id)->first();
                    $latestBalance = $user->saldo;
                    $user->update([
                        'saldo' => $user->saldo + $data->amount_received
                    ]);

                    createMutation($user->id, 'Kredit', 'Deposit Melalui ' . $data->payment_method . '.', $data->amount_received, $latestBalance, $user->saldo, $data->merchant_ref);
                    createMutation($user->id, 'Debet', 'Fee Deposit ' . $data->payment_method . '.', $data->total_fee, $latestBalance, $user->saldo, $data->merchant_ref);

                    $result = [
                        'app_name' => env('APP_NAME'),
                        'name' => $deposit->user->name,
                        'username' => $deposit->user->username,
                        'phone' => $deposit->user->phone,
                        'email' => $deposit->user->email,
                        'shop_name' => $deposit->user->shop_name,
                        'address' => $deposit->user->address,
                        'saldo' => 'Rp' . number_format($deposit->user->saldo, 0, '.', '.'),
                        'invoice' => $deposit->invoice,
                        'method' => $deposit->method,
                        'pay_code' => $deposit->pay_code,
                        'pay_url' => $deposit->pay_url,
                        'checkout_url' => $deposit->checkout_url,
                        'nominal' => 'Rp' . number_format($deposit->nominal, 0, '.', '.'),
                        'total' => 'Rp' . number_format($deposit->total, 0, '.', '.'),
                        'fee' => 'Rp' . number_format($deposit->fee, 0, '.', '.'),
                        'amount_received' => 'Rp' . number_format($deposit->amount_received, 0, '.', '.'),
                        'status' => $deposit->status,
                        'paid_at' => $deposit->paid_at?->format('Y-m-d H:i:s'),
                        'expired_at' => $deposit->expired_at?->format('Y-m-d H:i:s'),
                        'created_at' => $deposit->created_at?->format('Y-m-d H:i:s'),
                        'url' => route('deposit.show', $deposit->invoice),
                    ];

                    if (WhatsappHelper::getStatus()) {
                        WhatsappHelper::sendMessage('deposit-notification-user', $result, $deposit->user->phone);
                    }

                    break;

                case 'EXPIRED':
                    $deposit->update(['status' => 'failed']);
                    break;

                case 'FAILED':
                    $deposit->update(['status' => 'failed']);
                    break;

                default:
                    return Response::json([
                        'success' => false,
                        'message' => 'Unrecognized payment status',
                    ]);
            }

            return Response::json(['success' => true]);
        }
    }

    public function callbackDigiflazz(Request $request)
    {
        $postData = $request->getContent();
        $secret = DigiflazzHelper::getWebhookSecret();
        $signature = 'sha1=' . hash_hmac('sha1', $postData, $secret);

        if ($request->header('X-Hub-Signature') === $signature) {
            $eventData = $request->input('data');
            Log::info('Webhook Event Data: ', ['data' => $eventData]);

            $refId = $eventData['ref_id'];
            $transaction = Transaction::where('invoice', $refId)->first();
            $user = User::find($transaction->user_id);
            $product = Prabayar::where('buyer_sku_code', $transaction->buyer_sku_code)->first();
            $currentBalance = $user->saldo;
            $profit = SettingProfit::first();

            // Check Status
            $status = $eventData['status'];

            if ($status === 'Gagal') {
                $user->update([
                    'saldo' => $user->saldo + $transaction->price
                ]);

                createMutation($user->id, 'Kredit', 'Refund Saldo', $transaction->price, $user->saldo, $currentBalance, $transaction->invoice);
            }

            if ($user->agen_reseller_id !== null) {
                $reseller = User::find($user->agen_reseller_id);
                $profitResellerLatest = $reseller->profit_reseller;

                $reseller->update([
                    'profit_reseller' => $reseller->profit_reseller + ($transaction->price * ($profit->persentase / 100)),
                ]);

                Profit::create([
                    'user_id' => $reseller->id,
                    'transaction_id' => $transaction->id,
                    'agen' => $user->name,
                    'total_profit' => $transaction->price * ($profit->persentase / 100),
                ]);

                createMutation($reseller->id, 'Kredit', 'Mendapatkan Profit Dari ' . $user->name . '. Pembelian: ' . $product->product_name . '.', $transaction->price * ($profit->persentase / 100), $profitResellerLatest, $reseller->profit_reseller, $transaction->invoice);
            }

            $transaction->update([
                'message' => $eventData['message'],
                'status' => $eventData['status'],
                'sn' => $eventData['sn']
            ]);

            if ($transaction->target || preg_match('/^(08|62)\d+$/', $transaction->target)) {
                $dataNotif = [
                    'app_name' => env('APP_NAME'),
                    'name' => $transaction->user->name,
                    'username' => $transaction->user->username,
                    'phone' => $transaction->user->phone,
                    'email' => $transaction->user->email,
                    'shop_name' => $transaction->user->shop_name,
                    'address' => $transaction->user->address,
                    'saldo' => 'Rp' . number_format($transaction->user->saldo, 0, '.', '.'),
                    'invoice' => $transaction->invoice,
                    'target' => $transaction->target,
                    'product_name' => $transaction->product_name,
                    'price' => '-',
                    'customer_no' => $transaction->customer_no,
                    'customer_name' => $transaction->customer_name,
                    'admin' => $transaction->admin,
                    'description' => $transaction->description,
                    'message' => $transaction->message,
                    'sn' => $transaction->sn,
                    'selling_price' => $transaction->selling_price,
                    'tarif' => $transaction->tarif,
                    'daya' => $transaction->daya,
                    'billing' => $transaction->billing,
                    'detail' => $transaction->detail,
                    'status' => $transaction->status,
                    'type' => $transaction->type,
                    'created_at' => $transaction->created_at?->format('Y-m-d H:i:s'),
                ];

                if (WhatsappHelper::getStatus()) {
                    WhatsappHelper::sendMessage('transaction-notification-user', $dataNotif, $transaction->target);
                }
            }


            return response('Webhook received successfully', 200);
        } else {
            Log::warning('Invalid signature. Webhook ignored');
            return response('Invalid signature', 403);
        }
    }

    public function callbackPaydisini(Request $request)
    {
        $setting = SettingProvider::where('name', 'paydisini')->first();

        if ($request->input('key') == $setting->api_key) {
            $payment_id = '1234';
            $status = $request->input('status');
            $signature = $request->input('signature');
            $sign = md5($setting->api_key . $payment_id . 'CallbackStatus');

            if ($signature != $sign) {
                $result = ['success' => false];
            } else if ($status == 'Success') {
                // createMutation(1, 'Kredit', 'Tester', 100000, 100000, 410000, 'TST1234');
                $result = ['success' => true];
            } else {
                $result = ['success' => false, 'message' => 'sign salah'];
            }
        } else {
            $result = ['success' => false, 'message' => 'ip not allowed'];
        }

        return response()->json($result);
    }
}
