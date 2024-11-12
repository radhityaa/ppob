<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Helpers\MyHelper;
use App\Helpers\OtpHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\WhatsappHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['phone'] = MyHelper::formatPhoneNumber($data['phone']);

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,username'],
            'address' => ['required'],
            'shop_name' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'min:10', 'max:14', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            // Pesan error kustom
            'username.required' => 'Username Harus Diisi!',
            'username.min' => 'Username minimal 3 karakter!',
            'username.max' => 'Username maksimal 20 karakter!',
            'username.unique' => 'Username sudah digunakan!',
            'username.regex' => 'Nama pengguna hanya boleh berisi huruf (A-Z atau a-z atau 0-9) tanpa spasi!',
            'email.email' => 'Format Email salah!',
            'email.unique' => 'Email sudah digunakan!',
            'phone.required' => 'Nomor harus diisi!',
            'phone.unique' => 'Nomor sudah digunakan!',
            'name.required' => 'Nama harus diisi!',
            'address.required' => 'Alamat harus diisi!',
            'shop_name.required' => 'Nama Toko harus diisi!',
            'password.required' => 'Password harus diisi!'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'shop_name' => $data['shop_name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => MyHelper::formatPhoneNumber($data['phone']),
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('member');

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        OtpHelper::sendOtp($user);
        Auth::logout();
        session(['otp_user_id' => $user->id]);
        return redirect()->route('otp.verify.show');
    }
}
