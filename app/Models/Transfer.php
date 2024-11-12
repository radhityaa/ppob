<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['user'];

    public function toArray()
    {
        return [
            'user' => $this->user,
            'username' => $this->username,
            'invoice' => $this->invoice,
            'amount' => 'Rp ' . number_format($this->amount, 0, '.', '.'),
            'description' => $this->description,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i'),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'invoice';
    }
}
