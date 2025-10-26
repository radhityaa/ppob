<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipSocialMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_vipayment',
        'category',
        'min',
        'max',
        'name',
        'note',
        'price',
        'price_member',
        'price_agen',
        'price_reseller',
        'status',
    ];
}
