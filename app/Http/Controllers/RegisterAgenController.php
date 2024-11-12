<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\WhatsappHelper;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterAgenController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:reseller|admin']);
    }

    public function index(Request $request)
    {
        $title = "Daftar Agen";

        if ($request->ajax()) {

            $data = User::where('agen_reseller_id', Auth::user()->id)->latest()->get();

            return DataTables::make($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        return view('agen.register', compact('title'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,username'],
            'address' => ['required'],
            'shop_name' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'min:10', 'max:14', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
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

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'fields' => $validator->errors()
            ], 400);
        }

        $random = Str::random(10);
        $password = bcrypt($random);
        $phone = MyHelper::formatPhoneNumber($request['phone']);

        $user = User::create([
            'agen_reseller_id' => Auth::user()->id,
            'name' => $request['name'],
            'username' => $request['username'],
            'shop_name' => $request['shop_name'],
            'email' => $request['email'],
            'phone' => $phone,
            'address' => $request['address'],
            'saldo' => 0,
            'status' => 'aktif',
            'password' => $password,
        ]);

        // assign role
        $user->assignRole('agen');

        $dataNotif = [
            'app_name' => env('APP_NAME'),
            'reseller_name' => Auth::user()->name,
            'shop_name_reseller' => Auth::user()->shop_name,
            'name' => $user->name,
            'shop_name' => $user->shop_name,
            'username' => $user->username,
            'email' => $user->email,
            'address' => $user->address,
            'saldo' => $user->saldo,
            'password' => $random,
            'created_at' => $user->created_at->format('d-m-Y H:i:s'),
        ];

        if (WhatsappHelper::getStatus()) {
            WhatsappHelper::sendMessage('register-agen', $dataNotif, $phone);
        }

        return response()->json([
            'success' => true,
            'message' => 'Agen berhasil didaftarkan',
        ]);
    }
}
