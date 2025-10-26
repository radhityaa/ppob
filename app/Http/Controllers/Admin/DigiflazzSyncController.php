<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DigiflazzSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class DigiflazzSyncController extends Controller
{
    protected $syncService;

    public function __construct()
    {
        $this->syncService = new DigiflazzSyncService();
        $this->middleware(['role:admin'])->only(['index', 'syncAll', 'syncCategory', 'stats', 'clearCache']);
    }

    /**
     * Display sync dashboard
     */
    public function index()
    {
        $stats = $this->syncService->getSyncStats();
        $lastRun = Cache::get('digiflazz_sync_last_run');

        return view('admin.digiflazz-sync.index', compact('stats', 'lastRun'));
    }

    /**
     * Manual sync all products
     */
    public function syncAll()
    {
        try {
            $results = $this->syncService->syncAllProducts();

            return response()->json([
                'success' => true,
                'message' => 'Sync completed successfully',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            Log::error('Manual Digiflazz sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync specific category
     */
    public function syncCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string'
        ]);

        try {
            $result = $this->syncService->syncCategory($request->category);

            return response()->json([
                'success' => true,
                'message' => "Category {$request->category} synced successfully",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error("Manual Digiflazz sync failed for category {$request->category}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sync statistics
     */
    public function stats()
    {
        try {
            $stats = $this->syncService->getSyncStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear sync cache
     */
    public function clearCache()
    {
        try {
            $this->syncService->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }
}
