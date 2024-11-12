<?php

namespace App\Http\Controllers\Api;

use App\Helpers\TripayHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiDepositListResource;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    public function checkUser(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor ' . $request->phone . ' '
            ]);
        }
    }

    public function checkSaldo(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric'
        ]);

        $user = User::where('phone', $request->phone)->first();

        return response()->json([
            'success' => true,
            'message' => number_format($user->saldo, 0, '.', '.'),
        ]);
    }

    public function depositChannels(Request $request)
    {
        $channels = PaymentMethod::where('status', 1)->get();
        $channelData = [];

        foreach ($channels as $channel) {
            if ($channel->percent_fee > 0) {
                $fee = $channel->percent_fee . '%';
            } else {
                $fee = number_format($channel->fee, 0, '.', '.');
            }

            $channelData[] = [
                'name' => $channel->name,
                'fee' => $fee,
                'group' => $channel->group,
                'code' => $channel->code,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment Method',
            'data' => $channelData
        ]);
    }

    public function deposit(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        $nominal = formatRupiahToNumber($request->nominal);
        $deposit = Deposit::where(['user_id' => $user->id, 'status' => 'unpaid'])->first();

        if ($deposit) {
            return response()->json([
                'success' => false,
                'data' => $deposit->invoice
            ]);
        }

        $orderItems = [
            [
                'name'        => 'Deposit Saldo',
                'price'       => $nominal,
                'quantity'    => 1,
            ]
        ];

        $response = TripayHelper::createDepositWa($nominal, 'QRIS2', $orderItems, $user);
        $channel = PaymentMethod::where('code', 'QRIS2')->first();

        $flatFee = $channel->fee;
        $percentFee = $channel->percent_fee;
        $fee = $flatFee;

        if ($percentFee > 0 && $fee > 0) {
            $fee = ($nominal * ($percentFee / 100) + $channel->fee);
        }

        if (!$response->success) {
            return response()->json([
                'success' => true,
                'message' => $response->message,
            ], 400);
        }

        $expired_time = Carbon::createFromTimestamp($response->data->expired_time)->toDateTimeString();

        $result = Deposit::create([
            'user_id' => $user->id,
            'invoice' => $response->data->merchant_ref,
            'method' => $response->data->payment_name,
            'nominal' => $nominal,
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
            'name' => $user->name,
            'username' => $user->username,
            'phone' => $user->phone,
            'email' => $user->email,
            'shop_name' => $user->shop_name,
            'address' => $user->address,
            'saldo' => 'Rp' . number_format($user->saldo, 0, '.', '.'),
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

        return response()->json([
            'success' => true,
            'message' => 'Topup Saldo Berhasil',
            'data' =>  $data
        ]);
    }

    public function depositDetail(Request $request)
    {
        $deposit = Deposit::where('invoice', $request->invoice)->first();
        $user = User::where('phone', $request->phone)->first();

        if (!$deposit || $deposit->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Deposit Tidak Ditemukan'
            ]);
        }

        $data = [
            'app_name' => env('APP_NAME'),
            'name' => $user->name,
            'username' => $user->username,
            'phone' => $user->phone,
            'email' => $user->email,
            'shop_name' => $user->shop_name,
            'address' => $user->address,
            'saldo' => 'Rp' . number_format($user->saldo, 0, '.', '.'),
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
        ];

        return response()->json([
            'success' => true,
            'data' =>  $data
        ]);
    }

    public function depositList(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        $deposits = Deposit::where('user_id', $user->id)->latest()->get();

        return response()->json([
            'success' => true,
            'data' => ApiDepositListResource::collection($deposits)
        ]);
    }
}
