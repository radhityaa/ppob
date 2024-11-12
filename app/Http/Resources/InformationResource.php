<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'slug' => $this->slug,
            'category' => $this->categoryInformation,
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
