<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'description', 'amount', 'latest_balance', 'current_balance', 'invoice'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
