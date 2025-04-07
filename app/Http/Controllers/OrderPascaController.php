<?php

namespace App\Http\Controllers;

use App\Models\Pascabayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderPascaController extends Controller
{
    public function pln()
    {
        $title = 'Pembayaran PLN Pascabayar';
        $data = Pascabayar::where('brand', 'PLN PASCABAYAR')->first();
        $role = Auth::user()->getRoleNames()[0];

        return view('orders.pascabayar.pln', compact('title', 'data', 'role'));
    }
}
