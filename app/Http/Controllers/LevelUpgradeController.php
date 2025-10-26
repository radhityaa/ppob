<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\LevelUpgrade;
use App\Models\UserLevel;
use App\Models\User;
use App\Models\Mutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LevelUpgradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show upgrade level page
     */
    public function index()
    {
        $user = Auth::user();
        $currentRole = $user->roles[0] ?? null;
        $currentLevel = $currentRole ? Level::where('name', $currentRole->name)->first() : null;
        $nextLevel = $this->getNextLevel($currentLevel);
        $upgradePrice = $this->getUpgradePrice($currentLevel, $nextLevel);
        $canUpgrade = $this->canUpgrade($currentLevel);

        // Get all levels for display (only show current level and higher levels)
        $levels = Level::active()->ordered()->get();

        // Filter out lower levels if user has a current level
        if ($currentLevel) {
            $levels = $levels->filter(function ($level) use ($currentLevel) {
                return $level->sort_order >= $currentLevel->sort_order;
            });
        }

        // Get user's upgrade history
        $upgradeHistory = $user->levelUpgrades()->with(['fromLevel', 'toLevel'])->latest()->get();

        return view('level-upgrade.index', compact(
            'user',
            'currentLevel',
            'nextLevel',
            'upgradePrice',
            'canUpgrade',
            'levels',
            'upgradeHistory'
        ));
    }

    /**
     * Process level upgrade request
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate request
        $request->validate([
            'to_level_id' => 'required|exists:levels,id',
        ]);

        $currentRole = $user->roles[0] ?? null;
        $currentLevel = $currentRole ? Level::where('name', $currentRole->name)->first() : null;
        $toLevel = Level::findOrFail($request->to_level_id);

        // Check if user can upgrade
        if (!$this->canUpgrade($currentLevel)) {
            return redirect()->back()->with('error', 'Anda tidak dapat melakukan upgrade level saat ini.');
        }

        // Check if target level is higher than current level
        if ($currentLevel && !$toLevel->isHigherThan($currentLevel)) {
            return redirect()->back()->with('error', 'Level tujuan harus lebih tinggi dari level saat ini.');
        }

        // Check if user is trying to downgrade (not allowed)
        if ($currentLevel && $toLevel->sort_order <= $currentLevel->sort_order) {
            return redirect()->back()->with('error', 'Tidak dapat downgrade level. Hanya bisa upgrade ke level yang lebih tinggi.');
        }

        // Calculate upgrade price
        $upgradePrice = $this->getUpgradePrice($currentLevel, $toLevel);

        // Check if user has enough saldo
        if ($user->saldo < $upgradePrice) {
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk upgrade level.');
        }

        DB::beginTransaction();
        try {
            // Deduct saldo
            $user->decrementSaldo($upgradePrice);

            // Create upgrade request
            $upgrade = LevelUpgrade::create([
                'user_id' => $user->id,
                'from_level_id' => $currentLevel ? $currentLevel->id : null,
                'to_level_id' => $toLevel->id,
                'upgrade_price' => $upgradePrice,
            ]);

            // Create mutation record
            Mutation::create([
                'user_id' => $user->id,
                'type' => 'Debet',
                'amount' => $upgradePrice,
                'description' => "Upgrade level ke {$toLevel->display_name}",
                'latest_balance' => $user->saldo,
                'current_balance' => $user->saldo,
                'invoice' => "UPGRADE-{$upgrade->id}"
            ]);

            // Update user role using Spatie permission
            $user->syncRoles([strtolower($toLevel->name)]);

            DB::commit();

            return redirect()->route('level-upgrade.index')
                ->with('success', 'Permintaan upgrade level berhasil.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses upgrade level.');
        }
    }

    /**
     * Calculate upgrade price between levels
     */
    private function calculateUpgradePrice($fromLevel, $toLevel)
    {
        if (!$fromLevel) {
            return 0; // First level is free
        }

        $levelDifference = $toLevel->sort_order - $fromLevel->sort_order;
        $pricePerLevel = 100000; // Default price per level upgrade

        return $pricePerLevel * $levelDifference;
    }

    /**
     * Get next level for upgrade
     */
    private function getNextLevel($currentLevel)
    {
        if (!$currentLevel) {
            return Level::where('name', 'member')->first();
        }
        return Level::where('sort_order', '>', $currentLevel->sort_order)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();
    }

    /**
     * Get upgrade price between levels
     */
    private function getUpgradePrice($currentLevel, $nextLevel)
    {
        if (!$currentLevel || !$nextLevel) {
            return 0;
        }

        $levelDifference = $nextLevel->sort_order - $currentLevel->sort_order;
        $pricePerLevel = 100000; // Default price per level upgrade

        return $pricePerLevel * $levelDifference;
    }

    /**
     * Check if user can upgrade
     */
    private function canUpgrade($currentLevel)
    {
        if (!$currentLevel) {
            return true; // Can upgrade from no level to first level
        }

        $nextLevel = $this->getNextLevel($currentLevel);
        return $nextLevel !== null;
    }
}
