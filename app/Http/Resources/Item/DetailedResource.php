<?php

namespace App\Http\Resources\Item;

use App\Http\Resources\Category\DefaultResource as CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ...$this->only('id', 'name', 'place', 'rating', 'short_description', 'link'),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'description' => $this->parsedDescription(),
            'features' => $this->parsedFeatures(),
            'icon_url' => $this->fullIconUrl(),
            'image_url' => $this->fullImageUrl(),
        ];
    }
}