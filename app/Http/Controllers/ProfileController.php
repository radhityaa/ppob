<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelper;
use App\Http\Requests\AccountEditRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    public function update(AccountEditRequest $request, User $user)
    {
        if ($request->currentPassword) {
            if (Hash::check($request->currentPassword, $user->password)) {
                $user->update([
                    'password' =>  Hash::make($request->password),
                ]);
            } else {
                Alert::error('Gagal', 'Password Lama Salah');
                return back();
            }
        }

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'phone' => MyHelper::formatPhoneNumber($request->phone ?? $user->phone),
            'shop_name' => $request->shop_name ?? $user->shop_name,
            'address' => $request->address ?? $user->address,
        ]);

        Alert::success('Berhasil', 'Akun Berhasil Diubah');
        return back();
    }

    public function account(User $user)
    {
        return view('profile.account', compact('user'));
    }

    public function shop(User $user)
    {
        return view('profile.shop', compact('user'));
    }

    public function security(Request $request, User $user)
    {
        return view('profile.security', compact('user'));
    }
}
