<?php

namespace App\Http\Controllers;

use App\Helpers\DigiflazzHelper;
use App\Helpers\TripayHelper;
use App\Helpers\WhatsappHelper;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

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
                    $user->update([
                        'saldo' => $user->saldo + $data->amount_received
                    ]);

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

            $transaction->update([
                'message' => $eventData['message'],
                'status' => $eventData['status'],
                'sn' => $eventData['sn']
            ]);

            return response('Webhook received successfully', 200);
        } else {
            Log::warning('Invalid signature. Webhook ignored');
            return response('Invalid signature', 403);
        }
    }
}
