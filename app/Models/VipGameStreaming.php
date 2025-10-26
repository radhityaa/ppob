<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipGameStreaming extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'game',
        'name',
        'price',
        'price_member',
        'price_agen',
        'price_reseller',
        'server',
        'status',
    ];
}
