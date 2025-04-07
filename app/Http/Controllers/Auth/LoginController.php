<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\OtpHelper;
use App\Helpers\WhatsappHelper;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function authenticated(Request $request, $user)
    {
        if ($this->attemptLogin($request)) {
            if (WhatsappHelper::getStatus()) {
                // Generate OTP
                $user = $this->guard()->user();

                // Simpan OTP ke sesi
                session(['otp_user_id' => $user->id]);

                // Kirim OTP ke WhatsApp
                OtpHelper::sendOtp($user);

                // Logout sementara, lalu arahkan ke halaman OTP
                $this->guard()->logout();

                return redirect()->route('otp.verify.show');
            }

            // Jika login sukses, arahkan ke halaman dashboard
            return redirect()->route('login');
        }

        // Jika login gagal, kembali ke halaman login dengan error
        return $this->sendFailedLoginResponse($request);
    }
}
