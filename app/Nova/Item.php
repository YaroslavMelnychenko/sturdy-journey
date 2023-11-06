<?php

namespace App\Nova;

use App\Models;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Item extends Resource
{
    public static $model = Models\Item::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'place', 'rating',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            Fields\ID::make()->sortable(),

            Fields\BelongsTo::make(__('Category'), 'category', Category::class)
                ->showCreateRelationButton()
                ->rules('required')
                ->sortable()
                ->filterable(),

            Fields\Text::make(__('Name'), 'name')
                ->rules('required', 'string', 'max:255')
                ->sortable(),

            Fields\Text::make(__('Place'), 'place')
                ->rules('required', 'string', 'max:255')
                ->sortable(),

            Fields\Number::make(__('Rating'), 'rating')
                ->rules('required', 'min:0', 'max:5')
                ->min(0)
                ->max(5)
                ->step(0.1)
                ->sortable()
                ->filterable(),

            Fields\URL::make(__('Rating url'), 'rating_url')
                ->rules('required', 'url', 'max:255')
                ->displayUsing(function () {
                    return $this->rating_url;
                })
                ->hideFromIndex(),

            Fields\Textarea::make(__('Short description'), 'short_description')
                ->rules('required', 'string', 'max:5000'),

            Fields\URL::make(__('Link'), 'link')
                ->rules('required', 'url', 'max:255')
                ->displayUsing(function () {
                    return $this->link;
                })
                ->hideFromIndex(),

            Fields\KeyValue::make(__('Description'), 'description')
                ->rules('required', 'json')
                ->keyLabel(__('Heading'))
                ->valueLabel(__('Text'))
                ->actionText(__('Add paragraph')),

            Fields\KeyValue::make(__('Features'), 'features')
                ->rules('required', 'json')
                ->keyLabel(__('Heading'))
                ->valueLabel(__('Text'))
                ->actionText(__('Add feature')),

            Fields\KeyValue::make(__('SEO'), 'seo')
                ->rules('required', 'json')
                ->keyLabel(__('Heading'))
                ->valueLabel(__('Text'))
                ->actionText(__('Add record')),

            Fields\Image::make(__('Icon'), 'icon')
                ->rules('image', 'max:'. 10 * 1024)
                ->creationRules('required')
                ->updateRules('nullable')
                ->store(Storage\BaseStorage::folder('items/icons'))
                ->indexWidth(50)
                ->detailWidth(50)
                ->deletable(false),

            Fields\Image::make(__('Image'), 'image')
                ->rules('image', 'max:'. 10 * 1024)
                ->creationRules('required')
                ->updateRules('nullable')
                ->store(Storage\BaseStorage::folder('items/images'))
                ->hideFromIndex()
                ->deletable(false),

            ...$this->getTimestampsFields($request),
        ];
    }
}
