<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ...$this->only('id', 'name', 'place', 'rating', 'short_description', 'link'),
            'icon_url' => $this->fullIconUrl(),
        ];
    }
}