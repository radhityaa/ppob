<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\MyHelper;
use App\Helpers\WhatsappHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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
            'username' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z]+$/', 'unique:users,username'],
            'address' => ['required'],
            'shop_name' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'min:10', 'max:14', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            // Pesan error kustom
            'username.required' => 'Username is required!',
            'username.min' => 'Username must be at least 3 characters!',
            'username.max' => 'Username can be up to 20 characters!',
            'username.unique' => 'Username is already taken!',
            'username.regex' => 'Username can only contain letters (A-Z or a-z) without spaces or numbers!',
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

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::createDevice($user);
        }

        $user->assignRole('member');

        return $user;
    }
}
