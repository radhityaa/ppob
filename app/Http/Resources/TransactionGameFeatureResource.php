<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionGameFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice' => $this->invoice,
            'data_no' => $this->data_no,
            'data_zone' => $this->data_zone,
            'note' => $this->note,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d/m/Y H:i:s'),
            'game' => $this->vipGameStreaming->game,
            'name' => $this->vipGameStreaming->name,
        ];
    }
}
