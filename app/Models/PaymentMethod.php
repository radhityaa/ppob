<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'group', 'code', 'name', 'fee', 'provider', 'status', 'icon_url', 'percent_fee', 'bank_name', 'bank_account_number', 'bank_account_name'];
}
