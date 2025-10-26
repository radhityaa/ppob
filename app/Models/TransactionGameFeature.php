<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionGameFeature extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'vip_game_streaming_id', 'invoice', 'trxid', 'data_no', 'data_zone', 'status', 'note', 'original_price', 'selling_price', 'margin'];
    protected $with = ['user', 'vipGameStreaming'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vipGameStreaming()
    {
        return $this->belongsTo(VipGameStreaming::class, 'vip_game_streaming_id', 'id');
    }
}
