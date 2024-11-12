<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeItem extends Model
{
    use HasFactory;

    protected $fillable = ['recharge_title_id', 'route', 'src', 'label'];

    public function rechargeTitle()
    {
        return $this->belongsTo(RechargeTitle::class);
    }
}
