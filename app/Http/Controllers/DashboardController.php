<?php

namespace App\Http\Controllers;

use App\Models\RechargeTitle;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $transactionsToday = Transaction::transactionsToday();
        $transactionsMonth = Transaction::transactionsMonth();
        $usedBalanceToday = Transaction::usedBalanceToday();
        $usedBalanceMonth = Transaction::usedBalanceMonth();

        $listServices = RechargeTitle::with('rechargeItems')->get();

        return view('dashboard', compact('listServices', 'transactionsToday', 'transactionsMonth', 'usedBalanceToday', 'usedBalanceMonth'));
    }
}
