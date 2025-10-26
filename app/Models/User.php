<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'information_dismissed_at' => 'datetime',
    ];

    protected $with = ['roles', 'userLevel'];

    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * Get user's current level
     */
    public function userLevel()
    {
        return $this->hasOne(UserLevel::class);
    }

    /**
     * Get user's level upgrades
     */
    public function levelUpgrades()
    {
        return $this->hasMany(LevelUpgrade::class);
    }

    /**
     * Get user's mutations
     */
    public function mutations()
    {
        return $this->hasMany(Mutation::class);
    }

    /**
     * Get user's current level name
     */
    public function getCurrentLevelName()
    {
        return $this->userLevel ? $this->userLevel->level_name : 'member';
    }

    /**
     * Get user's current level display name
     */
    public function getCurrentLevelDisplayName()
    {
        return $this->userLevel ? $this->userLevel->level_display_name : 'Member';
    }

    /**
     * Check if user can upgrade level
     */
    public function canUpgradeLevel()
    {
        if (!$this->userLevel) {
            return true; // Can upgrade from no level to first level
        }
        return $this->userLevel->canUpgrade();
    }

    /**
     * Get next level for upgrade
     */
    public function getNextLevel()
    {
        if (!$this->userLevel) {
            return Level::where('name', 'member')->first();
        }
        return $this->userLevel->getNextLevel();
    }

    /**
     * Get upgrade price to next level
     */
    public function getUpgradePrice()
    {
        if (!$this->userLevel) {
            return 0; // First level is free
        }
        return $this->userLevel->getUpgradePrice();
    }

    public function decrementSaldo($amount)
    {
        $this->saldo -= $amount;
        $this->save();
    }

    public function incrementSaldo($amount)
    {
        $this->saldo += $amount;
        $this->save();
    }
}
