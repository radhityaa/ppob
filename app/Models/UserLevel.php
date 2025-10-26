<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level_id',
        'upgraded_at'
    ];

    protected $casts = [
        'upgraded_at' => 'datetime'
    ];

    /**
     * Get the user that owns the level
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the level
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get user's current level name
     */
    public function getLevelNameAttribute()
    {
        return $this->level->name;
    }

    /**
     * Get user's current level display name
     */
    public function getLevelDisplayNameAttribute()
    {
        return $this->level->display_name;
    }

    /**
     * Check if user can upgrade to next level
     */
    public function canUpgrade()
    {
        $nextLevel = $this->level->getNextLevel();
        return $nextLevel !== null;
    }

    /**
     * Get next level for upgrade
     */
    public function getNextLevel()
    {
        return $this->level->getNextLevel();
    }

    /**
     * Get upgrade price to next level
     */
    public function getUpgradePrice()
    {
        return $this->level->getUpgradePrice();
    }
}
