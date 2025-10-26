<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelUpgrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_level_id',
        'to_level_id',
        'upgrade_price',
    ];

    protected $casts = [
        'upgrade_price' => 'decimal:2',
    ];

    /**
     * Get the user requesting upgrade
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the from level
     */
    public function fromLevel(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'from_level_id');
    }

    /**
     * Get the to level
     */
    public function toLevel(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'to_level_id');
    }
}
