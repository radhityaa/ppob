<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Prabayar;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->hasRole('admin')) {
                abort(403, 'Hanya admin yang dapat mengakses dashboard ini.');
            }
            return $next($request);
        });
    }

    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get statistics for dashboard
        $stats = $this->getDashboardStats();
        $recentActivities = $this->getRecentActivities();
        $chartData = $this->getChartData();
        $systemHealth = $this->getSystemHealth();

        return view('admin.dashboard.index', compact('stats', 'recentActivities', 'chartData', 'systemHealth'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        return [
            // User Statistics
            'total_users' => User::count(),
            'new_users_today' => User::whereDate('created_at', $today)->count(),
            'new_users_this_month' => User::where('created_at', '>=', $thisMonth)->count(),
            'active_users' => User::where('updated_at', '>=', $today->subDays(7))->count(),

            // Deposit Statistics
            'total_deposits' => Deposit::count(),
            'deposits_today' => Deposit::whereDate('created_at', $today)->count(),
            'deposits_this_month' => Deposit::where('created_at', '>=', $thisMonth)->count(),
            'total_deposit_amount' => Deposit::sum('total'),
            'deposit_amount_today' => Deposit::whereDate('created_at', $today)->sum('total'),
            'deposit_amount_this_month' => Deposit::where('created_at', '>=', $thisMonth)->sum('total'),

            // Product Statistics
            'total_products' => Prabayar::count(),
            'active_products' => Prabayar::where('buyer_product_status', true)->count(),
            'inactive_products' => Prabayar::where('buyer_product_status', false)->count(),

            // Ticket Statistics
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'Open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'In Progress')->count(),
            'resolved_tickets' => Ticket::where('status', 'Resolved')->count(),
            'closed_tickets' => Ticket::where('status', 'Closed')->count(),
            'tickets_today' => Ticket::whereDate('created_at', $today)->count(),

            // Financial Statistics
            'pending_deposits' => Deposit::where('status', 'pending')->sum('total'),
            'approved_deposits' => Deposit::where('status', 'approved')->sum('total'),
            'rejected_deposits' => Deposit::where('status', 'rejected')->sum('total'),
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent deposits
        $recentDeposits = Deposit::with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($deposit) {
                return [
                    'type' => 'deposit',
                    'icon' => 'ti ti-credit-card',
                    'title' => 'Deposit Baru',
                    'description' => "{$deposit->user->name} melakukan deposit Rp " . number_format($deposit->total),
                    'time' => $deposit->created_at,
                    'status' => $deposit->status,
                    'amount' => $deposit->total
                ];
            });

        // Recent tickets
        $recentTickets = Ticket::with('user')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($ticket) {
                return [
                    'type' => 'ticket',
                    'icon' => 'ti ti-ticket',
                    'title' => 'Tiket Baru',
                    'description' => "{$ticket->user->name} membuat tiket: {$ticket->subject}",
                    'time' => $ticket->created_at,
                    'status' => $ticket->status,
                    'category' => $ticket->category
                ];
            });

        // Recent users
        $recentUsers = User::latest()
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'ti ti-user-plus',
                    'title' => 'User Baru',
                    'description' => "{$user->name} bergabung",
                    'time' => $user->created_at,
                    'status' => 'new'
                ];
            });

        return $activities
            ->merge($recentDeposits)
            ->merge($recentTickets)
            ->merge($recentUsers)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    /**
     * Get chart data for dashboard
     */
    private function getChartData()
    {
        // Last 30 days data
        $days = [];
        $depositData = [];
        $userData = [];
        $ticketData = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('M d');

            $depositData[] = Deposit::whereDate('created_at', $date)->sum('total');
            $userData[] = User::whereDate('created_at', $date)->count();
            $ticketData[] = Ticket::whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $days,
            'deposits' => $depositData,
            'users' => $userData,
            'tickets' => $ticketData
        ];
    }

    /**
     * Get system health information
     */
    private function getSystemHealth()
    {
        return [
            'database_status' => $this->checkDatabaseConnection(),
            'storage_status' => $this->checkStorageSpace(),
            'last_sync' => $this->getLastSyncTime(),
            'error_count' => $this->getErrorCount(),
            'uptime' => $this->getSystemUptime()
        ];
    }

    /**
     * Check database connection
     */
    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connected'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Database connection failed'];
        }
    }

    /**
     * Check storage space
     */
    private function checkStorageSpace()
    {
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;
        $percentage = ($usedSpace / $totalSpace) * 100;

        return [
            'status' => $percentage > 90 ? 'warning' : 'healthy',
            'percentage' => round($percentage, 2),
            'free_space' => $this->formatBytes($freeSpace),
            'total_space' => $this->formatBytes($totalSpace)
        ];
    }

    /**
     * Get last sync time
     */
    private function getLastSyncTime()
    {
        $lastSync = cache()->get('digiflazz_sync_last_run');
        return $lastSync ? $lastSync->diffForHumans() : 'Never';
    }

    /**
     * Get error count from logs
     */
    private function getErrorCount()
    {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            return substr_count($content, 'ERROR');
        }
        return 0;
    }

    /**
     * Get system uptime
     */
    private function getSystemUptime()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            return [
                'load_1min' => $load[0],
                'load_5min' => $load[1],
                'load_15min' => $load[2]
            ];
        }
        return null;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get dashboard data via AJAX
     */
    public function getData(Request $request)
    {
        $type = $request->get('type', 'stats');

        switch ($type) {
            case 'stats':
                return response()->json($this->getDashboardStats());
            case 'activities':
                return response()->json($this->getRecentActivities());
            case 'charts':
                return response()->json($this->getChartData());
            case 'health':
                return response()->json($this->getSystemHealth());
            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }
}
