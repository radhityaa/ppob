<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['user'];

    public function toArray()
    {
        return [
            'invoice' => $this->invoice,
            'target' => $this->target,
            'buyer_sku_code' => $this->buyer_sku_code,
            'product_name' => $this->product_name,
            'price' => number_format($this->price, 0, '.', '.'),
            'customer_no' => $this->customer_no,
            'customer_name' => $this->customer_name,
            'admin' => number_format($this->admin, 0, '.', '.'),
            'description' => $this->description,
            'message' => $this->message,
            'sn' => $this->sn,
            'selling_price' => number_format($this->selling_price, 0, '.', '.'),
            'tarif' => $this->tarif,
            'daya' => $this->daya,
            'billing' => $this->billing,
            'detail' => $this->detail,
            'status' => $this->status,
            'type' => $this->type,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i'),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
