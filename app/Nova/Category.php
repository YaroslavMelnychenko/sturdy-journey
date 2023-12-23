<?php

namespace App\Nova;

use App\Models;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Category extends Resource
{
    public static $model = Models\Category::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            Fields\ID::make()->sortable(),

            Fields\Text::make(__('Name'), 'name')
                ->rules('required', 'string', 'max:255')
                ->sortable(),

            Fields\Text::make(__('Name ru'), 'name_ru')
                ->rules('nullable', 'string', 'max:255')
                ->sortable(),

            Fields\Text::make(__('Name uk'), 'name_uk')
                ->rules('nullable', 'string', 'max:255')
                ->sortable(),

            Fields\Slug::make(__('Slug'), 'slug')
                ->from('name')
                ->rules('required', 'string', 'max:255', 'alpha_dash')
                ->creationRules('unique:categories,slug')
                ->updateRules('unique:categories,slug,{{resourceId}}')
                ->sortable(),

            ...$this->getTimestampsFields($request),

            Fields\HasMany::make(__('Items'), 'items', Item::class),
        ];
    }
}
