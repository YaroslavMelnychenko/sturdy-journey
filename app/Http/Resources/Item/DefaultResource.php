<?php

namespace App\Http\Resources\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefaultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            ...$this->only('id', 'name', 'rating', 'rating_url', 'link'),
            'short_description' => $this->__('short_description'),
            'place' => $this->__('place'),
            'seo' => $this->parsedSeo(),
            'icon_url' => $this->fullIconUrl(),
        ];
    }
}
