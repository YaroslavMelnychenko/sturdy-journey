<?php

namespace App\Nova;

use App\Models;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

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

            new Panel(__('Place'), [
                Fields\Text::make(__('Place'), 'place')
                    ->rules('required', 'string', 'max:255')
                    ->sortable(),

                Fields\Text::make(__('Place ru'), 'place_ru')
                    ->rules('required', 'string', 'max:255')
                    ->hideFromIndex(),

                Fields\Text::make(__('Place uk'), 'place_uk')
                    ->rules('required', 'string', 'max:255')
                    ->hideFromIndex(),
            ]),

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

            new Panel(__('Short description'), [
                Fields\Textarea::make(__('Short description'), 'short_description')
                    ->rules('required', 'string', 'max:5000'),

                Fields\Textarea::make(__('Short description ru'), 'short_description_ru')
                    ->rules('required', 'string', 'max:5000')
                    ->hideFromIndex(),

                Fields\Textarea::make(__('Short description uk'), 'short_description_uk')
                    ->rules('required', 'string', 'max:5000')
                    ->hideFromIndex(),
            ]),

            Fields\URL::make(__('Link'), 'link')
                ->rules('required', 'url', 'max:255')
                ->displayUsing(function () {
                    return $this->link;
                })
                ->hideFromIndex(),

            new Panel(__('Description'), [
                ...$this->makeRepeatableFields(__('Description'), 'description'),
                ...$this->makeRepeatableFields(__('Description ru'), 'description_ru'),
                ...$this->makeRepeatableFields(__('Description uk'), 'description_uk'),
            ]),

            new Panel(__('Features'), [
                ...$this->makeRepeatableFields(__('Features'), 'features'),
                ...$this->makeRepeatableFields(__('Features ru'), 'features_ru'),
                ...$this->makeRepeatableFields(__('Features uk'), 'features_uk'),
            ]),

            new Panel(__('SEO'), [
                ...$this->makeRepeatableFields(__('SEO'), 'seo'),
                ...$this->makeRepeatableFields(__('SEO ru'), 'seo_ru'),
                ...$this->makeRepeatableFields(__('SEO uk'), 'seo_uk'),
            ]),

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

    public function makeRepeatableFields(string $field_key, string $field): array
    {
        return [
            Fields\Repeater::make($field_key, $field)
                ->repeatables([
                    Repeater\KeyValue::make(),
                ])
                ->asJson()
                ->rules('required'),

            Fields\Text::make($field_key, function () use ($field) {
                $text = '';
                
                foreach ($this->{$field} ?? [] as $item) {
                    $text .= '<b>'.$item['fields']['heading'].'</b><br><p>'.$item['fields']['text'].'</p><br>';
                }

                return $text;
            })
                ->asHtml()
                ->exceptOnForms()
                ->hideFromIndex(),
        ];
    }
}
