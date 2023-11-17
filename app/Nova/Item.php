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

            Fields\Repeater::make(__('Description'), 'description')
                ->repeatables([
                    Repeater\KeyValue::make(),
                ])
                ->asJson()
                ->rules('required'),

            Fields\Text::make(__('Description'), function () {
                $text = '';

                foreach ($this->description as $item) {
                    $text .= '<b>'.$item['fields']['heading'].'</b><br><p>'.$item['fields']['text'].'</p><br>';
                }

                return $text;
            })
                ->asHtml()
                ->exceptOnForms()
                ->hideFromIndex(),

            Fields\Repeater::make(__('Features'), 'features')
                ->repeatables([
                    Repeater\KeyValue::make(),
                ])
                ->asJson()
                ->rules('required'),

            Fields\Text::make(__('Features'), function () {
                $text = '';

                foreach ($this->features as $item) {
                    $text .= '<b>'.$item['fields']['heading'].'</b><br><p>'.$item['fields']['text'].'</p><br>';
                }

                return $text;
            })
                ->asHtml()
                ->exceptOnForms()
                ->hideFromIndex(),

            Fields\Repeater::make(__('SEO'), 'seo')
                ->repeatables([
                    Repeater\KeyValue::make(),
                ])
                ->asJson()
                ->rules('required'),

            Fields\Text::make(__('SEO'), function () {
                $text = '';

                foreach ($this->seo as $item) {
                    $text .= '<b>'.$item['fields']['heading'].'</b><br><p>'.$item['fields']['text'].'</p><br>';
                }

                return $text;
            })
                ->asHtml()
                ->exceptOnForms()
                ->hideFromIndex(),

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
