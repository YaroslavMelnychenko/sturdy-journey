<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'place',
        'rating',
        'short_description',
        'link',
        'description',
        'features',
        'icon',
        'image',
    ];

    protected $casts = [
        'description' => 'array',
        'features' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
