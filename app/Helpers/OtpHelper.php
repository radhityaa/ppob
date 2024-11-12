<?php

namespace App\Helpers;

use App\Models\Otp;

class OtpHelper
{
    public static function sendOtp($user)
    {
        $otpCode = rand(100000, 999999); // 6 digits

        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(env('OTP_EXPIRATION_MINUTES', 5))
        ]);

        $data = [
            'app_name' => config('app.name'),
            'otp' => $otpCode
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('otp', $data, $user->phone);
        }

        return 'OTP telah dikirim ke nomor ' . $user->phone;
    }
}
