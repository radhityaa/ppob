<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_name' => $this->product_name,
            'category' => $this->category,
            'brand' => $this->brand,
            'type' => $this->type,
            'seller_name' => $this->seller_name,
            'price' => number_format($this->price, 0, '.', '.'),
            'buyer_sku_code' => $this->buyer_sku_code,
            'buyer_product_status' => $this->buyer_product_status ? 'Normal' : 'Gangguan',
            'seller_product_status' => $this->seller_product_status ? 'Normal' : 'Gangguan',
            'unlimited_stock' => $this->unlimited_stock ? 'Ya' : 'Tidak',
            'stock' => $this->stock == 0 ? 'Unlimited' : $this->stock,
            'multi' => $this->multi ? 'Ya' : 'Tidak',
            'cut_off' => $this->start_cut_off . ' s/d ' . $this->end_cut_off,
            'desc' => $this->desc,
            'provider' => $this->provider,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i'),
        ];
    }
}
