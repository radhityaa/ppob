<?php

namespace App\Http\Controllers;

use App\Models\Profit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProfitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:reseller|admin']);
    }

    public function index(Request $request)
    {
        $title = "Profit";

        if ($request->ajax()) {
            if (Auth::user()->hasRole('admin')) {
                $data = Profit::with('transaction')->latest()->get();
            } else {
                $data = Profit::where('user_id', Auth::user()->id)->with('transaction')->latest()->get();
            }

            return DataTables::make($data)
                ->addIndexColumn()
                ->editColumn('total_profit', function ($row) {
                    return 'Rp. ' . number_format($row->total_profit, 2, ',', '.');
                })
                ->addColumn('product', function ($row) {
                    return $row->transaction->product_name;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i:s');
                })
                ->make(true);
        }

        $statistics = [];

        // Profit Last Day
        $dailyProfits = DB::table('profits')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_profit) as daily_total')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30)) // collect data for the last 30 days
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Profit per minggu
        $weeklyProfits = DB::table('profits')
            ->select(
                DB::raw('YEARWEEK(created_at) as week'),
                DB::raw('SUM(total_profit) as weekly_total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(3)) // collect data for the last 3 months
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get();

        // Profit per bulan
        $monthlyProfits = DB::table('profits')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_profit) as monthly_total')
            )
            ->where('created_at', '>=', Carbon::now()->subYear()) // collect data for the last year
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Hitung persentase perubahan harian
        $statistics['daily'] = $this->calculatePercentageChange($dailyProfits, 'daily_total');

        // Hitung persentase perubahan mingguan
        $statistics['weekly'] = $this->calculatePercentageChange($weeklyProfits, 'weekly_total');

        // Hitung persentase perubahan bulanan
        $statistics['monthly'] = $this->calculatePercentageChange($monthlyProfits, 'monthly_total');

        $user = User::where('id', Auth::user()->id)->latest()->first();

        return view('profit.index', compact('title', 'statistics', 'user'));
    }

    // Fungsi untuk menghitung persentase perubahan
    private function calculatePercentageChange($profits, $totalField)
    {
        $previousTotal = null;
        $latestData = null;

        foreach ($profits as $profit) {
            $percentageChange = 0;

            // Calculate percentage change if there’s a previous total
            if ($previousTotal !== null && $previousTotal > 0) {
                $percentageChange = (($profit->$totalField - $previousTotal) / $previousTotal) * 100;
            }

            // Determine the period based on available data
            $period = $profit->date ?? $profit->week ?? $profit->month;

            // Store the latest data as an associative array
            $latestData = [
                'period' => $period,
                'total' => $profit->$totalField,
                'percentage_change' => $percentageChange,
            ];

            $previousTotal = $profit->$totalField;
        }

        return $latestData; // Return only the latest data entry
    }

    public function withdrawal()
    {
        $title = "Riwayat Penarikan";

        return view('profit.withdrawal', compact('title'));
    }
}
