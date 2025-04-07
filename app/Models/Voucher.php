<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "target_id",
        "invoice",
        "name",
        "slug",
        "code",
        "qty",
        "value",
        "type_target",
        "type_voucher",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function target()
    {
        return $this->belongsTo(User::class, "target_id");
    }
}
