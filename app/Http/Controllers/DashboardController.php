<?php

namespace App\Http\Controllers;

use App\Models\RechargeTitle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $listServices = RechargeTitle::with('rechargeItems')->get();

        return view('dashboard', compact('listServices'));
    }
}
