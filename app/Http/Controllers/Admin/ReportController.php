<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Prabayar;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\Mutation;
use App\Models\WithDrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Hanya admin yang dapat mengakses report ini.');
            }
            return $next($request);
        });
    }

    /**
     * Display report index
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $reports = $this->getReportData($dateFrom, $dateTo);

        return view('admin.report.index', compact('reports', 'dateFrom', 'dateTo'));
    }

    /**
     * Get comprehensive report data
     */
    private function getReportData($dateFrom, $dateTo)
    {
        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();

        return [
            // Financial Overview
            'financial' => $this->getFinancialReport($startDate, $endDate),

            // Transaction Reports
            'transactions' => $this->getTransactionReport($startDate, $endDate),

            // User Reports
            'users' => $this->getUserReport($startDate, $endDate),

            // Deposit Reports
            'deposits' => $this->getDepositReport($startDate, $endDate),

            // Product Reports
            'products' => $this->getProductReport($startDate, $endDate),

            // Ticket Reports
            'tickets' => $this->getTicketReport($startDate, $endDate),

            // Profit Analysis
            'profit' => $this->getProfitAnalysis($startDate, $endDate),

            // Daily Summary
            'daily_summary' => $this->getDailySummary($startDate, $endDate)
        ];
    }

    /**
     * Get financial report
     */
    private function getFinancialReport($startDate, $endDate)
    {
        // Total deposits
        $totalDeposits = Deposit::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('total');

        // Total withdrawals
        $totalWithdrawals = WithDrawal::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        // Pending deposits
        $pendingDeposits = Deposit::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->sum('total');

        // Pending withdrawals
        $pendingWithdrawals = WithDrawal::whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        return [
            'total_deposits' => $totalDeposits,
            'total_withdrawals' => $totalWithdrawals,
            'pending_deposits' => $pendingDeposits,
            'pending_withdrawals' => $pendingWithdrawals,
            'net_cash_flow' => $totalDeposits - $totalWithdrawals
        ];
    }

    /**
     * Get transaction report
     */
    private function getTransactionReport($startDate, $endDate)
    {
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with('prabayar')
            ->get();

        $totalTransactions = $transactions->count();
        $totalRevenue = $transactions->sum('total');
        $totalCost = $transactions->sum('price');
        $totalProfit = $totalRevenue - $totalCost;

        // Revenue by product type
        $revenueByType = $transactions->groupBy('prabayar.category')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'revenue' => $group->sum('total'),
                    'cost' => $group->sum('price'),
                    'profit' => $group->sum('total') - $group->sum('price')
                ];
            });

        return [
            'total_transactions' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'average_transaction' => $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0,
            'revenue_by_type' => $revenueByType
        ];
    }

    /**
     * Get user report
     */
    private function getUserReport($startDate, $endDate)
    {
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeUsers = User::whereBetween('updated_at', [$startDate, $endDate])->count();
        $totalUsers = User::count();

        // Users by status
        $usersByStatus = User::selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'new_users' => $newUsers,
            'active_users' => $activeUsers,
            'total_users' => $totalUsers,
            'users_by_status' => $usersByStatus
        ];
    }

    /**
     * Get deposit report
     */
    private function getDepositReport($startDate, $endDate)
    {
        $deposits = Deposit::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_deposits' => $deposits->count(),
            'total_amount' => $deposits->sum('total'),
            'approved_deposits' => $deposits->where('status', 'approved')->count(),
            'approved_amount' => $deposits->where('status', 'approved')->sum('total'),
            'pending_deposits' => $deposits->where('status', 'pending')->count(),
            'pending_amount' => $deposits->where('status', 'pending')->sum('total'),
            'rejected_deposits' => $deposits->where('status', 'rejected')->count(),
            'rejected_amount' => $deposits->where('status', 'rejected')->sum('total')
        ];
    }

    /**
     * Get product report
     */
    private function getProductReport($startDate, $endDate)
    {
        $products = Prabayar::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'new_products' => $products->count(),
            'active_products' => Prabayar::where('buyer_product_status', true)->count(),
            'inactive_products' => Prabayar::where('buyer_product_status', false)->count(),
            'products_by_category' => Prabayar::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
        ];
    }

    /**
     * Get ticket report
     */
    private function getTicketReport($startDate, $endDate)
    {
        $tickets = Ticket::whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_tickets' => $tickets->count(),
            'open_tickets' => Ticket::where('status', 'Open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'In Progress')->count(),
            'resolved_tickets' => Ticket::where('status', 'Resolved')->count(),
            'closed_tickets' => Ticket::where('status', 'Closed')->count(),
            'tickets_by_category' => Ticket::selectRaw('category, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('category')
                ->pluck('count', 'category')
        ];
    }

    /**
     * Get profit analysis
     */
    private function getProfitAnalysis($startDate, $endDate)
    {
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->with('prabayar')
            ->get();

        // Calculate profit margins
        $totalRevenue = $transactions->sum('price');
        $totalCost = $transactions->sum('price');
        $grossProfit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        // Profit by user type (based on price differences)
        $profitByUserType = [
            'member' => $transactions->sum(function ($transaction) {
                return $transaction->total - $transaction->price;
            }),
            'reseller' => $transactions->sum(function ($transaction) {
                return $transaction->total - ($transaction->prabayar->price_reseller ?? $transaction->price);
            }),
            'agen' => $transactions->sum(function ($transaction) {
                return $transaction->total - ($transaction->prabayar->price_agen ?? $transaction->price);
            })
        ];

        return [
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'gross_profit' => $grossProfit,
            'profit_margin' => $profitMargin,
            'profit_by_user_type' => $profitByUserType
        ];
    }

    /**
     * Get daily summary
     */
    private function getDailySummary($startDate, $endDate)
    {
        $days = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dayStart = $currentDate->copy()->startOfDay();
            $dayEnd = $currentDate->copy()->endOfDay();

            $dayData = [
                'date' => $currentDate->format('Y-m-d'),
                'transactions' => Transaction::whereBetween('created_at', [$dayStart, $dayEnd])->count(),
                'revenue' => Transaction::whereBetween('created_at', [$dayStart, $dayEnd])->sum('price'),
                'cost' => Transaction::whereBetween('created_at', [$dayStart, $dayEnd])->sum('price'),
                'deposits' => Deposit::whereBetween('created_at', [$dayStart, $dayEnd])->sum('total'),
                'users' => User::whereBetween('created_at', [$dayStart, $dayEnd])->count(),
                'tickets' => Ticket::whereBetween('created_at', [$dayStart, $dayEnd])->count()
            ];

            $dayData['profit'] = $dayData['revenue'] - $dayData['cost'];
            $days[] = $dayData;

            $currentDate->addDay();
        }

        return $days;
    }

    /**
     * Export report data
     */
    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $reports = $this->getReportData($dateFrom, $dateTo);

        // TODO: Implement export functionality (Excel, PDF, etc.)
        return response()->json([
            'message' => 'Export functionality will be implemented',
            'data' => $reports
        ]);
    }
}
