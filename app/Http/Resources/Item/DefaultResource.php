<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ...$this->only('id', 'name', 'place', 'rating', 'rating_url', 'short_description', 'link'),
            'seo' => $this->parsedSeo(),
            'icon_url' => $this->fullIconUrl(),
        ];
    }
}
