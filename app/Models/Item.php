<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'place',
        'rating',
        'rating_url',
        'short_description',
        'link',
        'description',
        'features',
        'seo',
        'icon',
        'image',
    ];

    protected $casts = [
        'description' => 'array',
        'features' => 'array',
        'seo' => 'array',
    ];

    public function parsedDescription(): array
    {
        $description = [];

        foreach ($this->description as $key => $value) {
            $description[] = [
                'heading' => $key,
                'paragraph' => $value,
            ];
        }

        return $description;
    }

    public function parsedFeatures(): array
    {
        $description = [];

        foreach ($this->features as $key => $value) {
            $description[] = [
                'heading' => $key,
                'text' => $value,
            ];
        }

        return $description;
    }

    public function parsedSeo(): array
    {
        $seo = [];

        if ($this->seo === null) {
            return $seo;
        }

        foreach ($this->seo as $key => $value) {
            $seo[] = [
                'heading' => $key,
                'text' => $value,
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

    public function fullImageUrl(): ?string
    {
        if ($this->image === null) {
            return null;
        }

        return Storage::url($this->image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
