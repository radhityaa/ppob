<?php

namespace App\Http\Controllers;

use App\Models\RechargeTitle;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();
        $today = now()->startOfDay();

        // Tampilkan modal jika news_dismissed_at null atau jika terakhir kali menutup modal bukan hari ini
        $showInformationModal = !$user->news_dismissed_at || $user->news_dismissed_at->lessThan($today);
        // dd($showInformationModal);

        return view('dashboard', compact('listServices', 'transactionsToday', 'transactionsMonth', 'usedBalanceToday', 'usedBalanceMonth', 'showInformationModal'));
    }
}
