<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BriefResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ...$this->only('id', 'name', 'slug'),
            'icon_url' => $this->fullIconUrl(),
        ];
    }
}
