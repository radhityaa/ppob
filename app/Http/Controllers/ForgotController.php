<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Helpers\WhatsappHelper;
use App\Models\ResetToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use RealRashid\SweetAlert\Facades\Alert;

class ForgotController extends Controller
{
    public function index()
    {
        return view('auth.passwords.wa');
    }

    public function sendVerificationCode(Request $request)
    {
        if (!$request->phone) {
            return back()->with(['error' => 'Nomor Whatsapp wajib diisi!']);
        }

        $phone = MyHelper::formatPhoneNumber($request->phone);

        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return back()->with(['error' => 'Nomor Whatsapp yang dimasukkan tidak ditemukan!']);
        }

        $token = Uuid::uuid4();
        ResetToken::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(env('OTP_EXPIRATION_MINUTES', 5))
        ]);

        $data = [
            'app_name' => config('app.name'),
            'name' => $user->name,
            'url' => route('reset.verify', ['phone' => $user->phone, 'token' => $token]),
            'exp_time' => env('OTP_EXPIRATION_MINUTES', 5)
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('reset-password', $data, $user->phone);
        }

        return back()->with(['success' => 'Kode verifikasi telah dikirim ke nomor ' . $user->phone]);
    }

    public function verify($phone, $token)
    {
        if (!$phone || !$token) {
            return redirect(route('login'));
        }

        $resetToken = ResetToken::where('phone', $phone)
            ->where('is_used', false)
            ->where('token', $token)
            ->latest()
            ->first();

        if (!$resetToken || $resetToken->isExpired()) {
            return redirect()->route('login')->with(['error' => 'Kode verifikasi salah atau kadaluarsa!']);
        }

        return view('auth.passwords.change-password', compact('token', 'phone'));
    }

    public function changePassword(Request $request, $phone, $token)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'Email harus diisi!',
            'email.exists' => 'Email yang dimasukkan tidak ditemukan!',
            'password.required' => 'Password harus diisi!',
            'password.min' => 'Password minimal 8 karakter!',
            'password.confirmed' => 'Password dan konfirmasi password harus sama!'
        ]);

        $resetToken = ResetToken::where('phone', $phone)->where('token', $token)->latest()->first();

        if (!$resetToken || $resetToken->isExpired()) {
            return redirect()->route('login')->with(['error' => 'Kode verifikasi salah atau kadaluarsa!']);
        }

        $user = User::where('phone', $phone)->where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with(['error' => 'Akun tidak ditemukan!']);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        $resetToken->delete();

        return redirect()->route('login')->with(['success' => 'Password berhasil diubah! Silahkan login kembali.']);
    }
}
