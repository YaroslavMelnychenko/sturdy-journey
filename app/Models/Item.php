<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Item extends Model
{
    use HasFactory,
        Traits\HasLocales;

    protected $fillable = [
        'category_id',
        'name',
        'place',
        'place_ru',
        'place_uk',
        'rating',
        'rating_url',
        'short_description',
        'short_description_ru',
        'short_description_uk',
        'link',
        'description',
        'description_ru',
        'description_uk',
        'features',
        'features_ru',
        'features_uk',
        'seo',
        'seo_ru',
        'seo_uk',
        'icon',
    ];

    protected $casts = [
        'description' => 'array',
        'description_ru' => 'array',
        'description_uk' => 'array',
        'features' => 'array',
        'features_ru' => 'array',
        'features_uk' => 'array',
        'seo' => 'array',
        'seo_ru' => 'array',
        'seo_uk' => 'array',
    ];

    public function parsedDescription(): array
    {
        $description = [];

        foreach ($this->__('description') as $item) {
            $description[] = [
                'heading' => $item['fields']['heading'],
                'paragraph' => $item['fields']['text'],
            ];
        }

        return $description;
    }

    public function parsedFeatures(): array
    {
        $description = [];

        foreach ($this->__('features') as $item) {
            $description[] = [
                'heading' => $item['fields']['heading'],
                'text' => $item['fields']['text'],
            ];
        }

        return $description;
    }

    public function parsedSeo(): array
    {
        $seo = [];

        if ($this->__('seo') === null) {
            return $seo;
        }

        foreach ($this->__('seo') as $item) {
            $seo[] = [
                'heading' => $item['fields']['heading'],
                'text' => $item['fields']['text'],
            ];
        }

        return $seo;
    }

    public function fullIconUrl(): ?string
    {
        if ($this->icon === null) {
            return null;
        }

        return Storage::url($this->icon);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
