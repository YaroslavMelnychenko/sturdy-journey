<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Item\DefaultResource as ItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ...$this->only('id', 'slug'),
            'name' => $this->__('name'),
            'items' => ItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
