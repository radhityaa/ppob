<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Get user levels for this level
     */
    public function userLevels(): HasMany
    {
        return $this->hasMany(UserLevel::class);
    }

    /**
     * Get upgrade requests from this level
     */
    public function upgradesFrom(): HasMany
    {
        return $this->hasMany(LevelUpgrade::class, 'from_level_id');
    }

    /**
     * Get upgrade requests to this level
     */
    public function upgradesTo(): HasMany
    {
        return $this->hasMany(LevelUpgrade::class, 'to_level_id');
    }

    /**
     * Get next level
     */
    public function getNextLevel()
    {
        return self::where('sort_order', '>', $this->sort_order)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();
    }

    /**
     * Get previous level
     */
    public function getPreviousLevel()
    {
        return self::where('sort_order', '<', $this->sort_order)
            ->where('is_active', true)
            ->orderBy('sort_order', 'desc')
            ->first();
    }

    /**
     * Check if this level is higher than another level
     */
    public function isHigherThan(Level $level)
    {
        return $this->sort_order > $level->sort_order;
    }

    /**
     * Get upgrade price to next level
     */
    public function getUpgradePrice()
    {
        $nextLevel = $this->getNextLevel();
        if (!$nextLevel) {
            return 0;
        }

        // Get upgrade price from settings or calculate based on level difference
        $pricePerLevel = 100000; // Default price per level upgrade
        $levelDifference = $nextLevel->sort_order - $this->sort_order;

        return $pricePerLevel * $levelDifference;
    }

    /**
     * Scope for active levels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
