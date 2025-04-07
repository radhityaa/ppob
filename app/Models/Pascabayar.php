<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pascabayar extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_name",
        "category",
        "brand",
        "seller_name",
        "admin",
        "commission",
        "buyer_sku_code",
        "buyer_product_status",
        "seller_product_status",
        "description",
        "provider",
    ];
}
