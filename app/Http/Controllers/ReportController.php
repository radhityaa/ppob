<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Deposit;
use App\Models\Mutation;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user report index
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $reports = $this->getUserReportData($dateFrom, $dateTo);

        return view('report.index', compact('reports', 'dateFrom', 'dateTo'));
    }

    /**
     * Get user report data
     */
    private function getUserReportData($dateFrom, $dateTo)
    {
        $startDate = Carbon::parse($dateFrom)->startOfDay();
        $endDate = Carbon::parse($dateTo)->endOfDay();
        $userId = Auth::id();

        return [
            // Transaction Reports
            'transactions' => $this->getUserTransactionReport($userId, $startDate, $endDate),

            // Financial Reports
            'financial' => $this->getUserFinancialReport($userId, $startDate, $endDate),

            // Mutation Reports
            'mutations' => $this->getUserMutationReport($userId, $startDate, $endDate),

            // Ticket Reports
            'tickets' => $this->getUserTicketReport($userId, $startDate, $endDate),

            // Daily Summary
            'daily_summary' => $this->getUserDailySummary($userId, $startDate, $endDate)
        ];
    }

    /**
     * Get user transaction report
     */
    private function getUserTransactionReport($userId, $startDate, $endDate)
    {
        $transactions = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('prabayar')
            ->get();

        $totalTransactions = $transactions->count();
        $totalSpent = $transactions->sum('price');
        $successfulTransactions = $transactions->where('status', 'success')->count();
        $failedTransactions = $transactions->where('status', 'failed')->count();

        // Transactions by product type
        $transactionsByType = $transactions->groupBy('prabayar.category')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_spent' => $group->sum('price'),
                    'successful' => $group->where('status', 'success')->count(),
                    'failed' => $group->where('status', 'failed')->count()
                ];
            });

        return [
            'total_transactions' => $totalTransactions,
            'total_spent' => $totalSpent,
            'successful_transactions' => $successfulTransactions,
            'failed_transactions' => $failedTransactions,
            'success_rate' => $totalTransactions > 0 ? ($successfulTransactions / $totalTransactions) * 100 : 0,
            'average_transaction' => $totalTransactions > 0 ? $totalSpent / $totalTransactions : 0,
            'transactions_by_type' => $transactionsByType
        ];
    }

    /**
     * Get user financial report
     */
    private function getUserFinancialReport($userId, $startDate, $endDate)
    {
        // Total deposits
        $totalDeposits = Deposit::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'approved')
            ->sum('total');

        // Pending deposits
        $pendingDeposits = Deposit::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'pending')
            ->sum('total');

        // Current balance
        $currentBalance = Auth::user()->saldo;

        // Total spent on transactions
        $totalSpent = Transaction::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        return [
            'total_deposits' => $totalDeposits,
            'pending_deposits' => $pendingDeposits,
            'current_balance' => $currentBalance,
            'total_spent' => $totalSpent,
            'net_flow' => $totalDeposits - $totalSpent
        ];
    }

    /**
     * Get user mutation report
     */
    private function getUserMutationReport($userId, $startDate, $endDate)
    {
        $mutations = Mutation::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalDebit = $mutations->where('type', 'Debet')->sum('amount');
        $totalCredit = $mutations->where('type', 'Kredit')->sum('amount');
        $totalMutations = $mutations->count();

        // Mutations by type
        $mutationsByType = $mutations->groupBy('description')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'debit' => $group->where('type', 'Debet')->sum('amount'),
                    'credit' => $group->where('type', 'Kredit')->sum('amount')
                ];
            });

        return [
            'total_mutations' => $totalMutations,
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'net_mutation' => $totalCredit - $totalDebit,
            'mutations_by_type' => $mutationsByType
        ];
    }

    /**
     * Get user ticket report
     */
    private function getUserTicketReport($userId, $startDate, $endDate)
    {
        $tickets = Ticket::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        return [
            'total_tickets' => $tickets->count(),
            'open_tickets' => Ticket::where('user_id', $userId)->where('status', 'Open')->count(),
            'in_progress_tickets' => Ticket::where('user_id', $userId)->where('status', 'In Progress')->count(),
            'resolved_tickets' => Ticket::where('user_id', $userId)->where('status', 'Resolved')->count(),
            'closed_tickets' => Ticket::where('user_id', $userId)->where('status', 'Closed')->count(),
            'tickets_by_category' => Ticket::where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
        ];
    }

    /**
     * Get user daily summary
     */
    private function getUserDailySummary($userId, $startDate, $endDate)
    {
        $days = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dayStart = $currentDate->copy()->startOfDay();
            $dayEnd = $currentDate->copy()->endOfDay();

            $dayData = [
                'date' => $currentDate->format('Y-m-d'),
                'transactions' => Transaction::where('user_id', $userId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count(),
                'spent' => Transaction::where('user_id', $userId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum('price'),
                'deposits' => Deposit::where('user_id', $userId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->sum('total'),
                'mutations' => Mutation::where('user_id', $userId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count(),
                'tickets' => Ticket::where('user_id', $userId)
                    ->whereBetween('created_at', [$dayStart, $dayEnd])
                    ->count()
            ];

            $dayData['net_flow'] = $dayData['deposits'] - $dayData['spent'];
            $days[] = $dayData;

            $currentDate->addDay();
        }

        return $days;
    }

    /**
     * Export user report data
     */
    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $reports = $this->getUserReportData($dateFrom, $dateTo);

        // TODO: Implement export functionality (Excel, PDF, etc.)
        return response()->json([
            'message' => 'Export functionality will be implemented',
            'data' => $reports
        ]);
    }
}
