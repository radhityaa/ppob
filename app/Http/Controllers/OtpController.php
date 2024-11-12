<?php

namespace App\Http\Controllers;

use App\Helpers\WhatsappHelper;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function sendOtp(User $user)
    {
        $otpCode = rand(100000, 999999);

        $findOtp = Otp::where('user_id', $user->id)->latest()->first();

        if ($findOtp) {
            $findOtp->delete();
        }

        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(env('OTP_EXPIRATION_MINUTES'))
        ]);

        $data = [
            'app_name' => config('app.name'),
            'otp' => $otpCode
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('otp', $data, $user->phone);
        }

        return response()->json(['message' => 'OTP telah dikirim.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6'
        ]);

        $otp = Otp::where('user_id', session('otp_user_id'))
            ->where('otp_code', $request->otp_code)
            ->where('is_used', false)
            ->first();

        if (!$otp || $otp->isExpired()) {
            return response()->json(['message' => 'Kode OTP salah atau sudah kedaluwarsa.'], 422);
        }

        $otp->delete();

        Auth::loginUsingId(session('otp_user_id'));
        session()->forget(['otp_user_id']);

        return response()->json(['message' => 'OTP berhasil diverifikasi.']);
    }

    public function resendOtp()
    {
        $lastOtp = Otp::where('user_id', session('otp_user_id'))->latest()->first();
        $user = User::find(session('otp_user_id'));

        if ($lastOtp && $lastOtp->created_at->diffInSeconds(now()) < 30) {
            return response()->json(['message' => 'Harap tunggu 30 detik untuk mengirim ulang OTP.'], 429);
        }

        return $this->sendOtp($user);
    }
}
