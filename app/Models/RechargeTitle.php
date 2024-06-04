<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeTitle extends Model
{
    use HasFactory;

    protected $fillable = ['title'];

    public function rechargeItems()
    {
        return $this->hasMany(RechargeItem::class);
    }
}
