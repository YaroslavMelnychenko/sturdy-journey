<?php

namespace App\Nova;

use App\Models;
use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Feedback extends Resource
{
    public static $model = Models\Feedback::class;

    public static $search = [
        'id', 'email',
    ];

    public function title()
    {
        return "{$this->email} ({$this->name}) #{$this->id}";
    }

    public function fields(NovaRequest $request)
    {
        return [
            Fields\ID::make()->sortable(),

            Fields\Text::make(__('Name'), 'name')
                ->rules('required', 'string', 'max:255')
                ->sortable(),

            Fields\Email::make(__('Email'), 'email')
                ->rules('required', 'email', 'max:255')
                ->sortable(),

            Fields\Textarea::make(__('Short description'), 'short_description')
                ->rules('required', 'string', 'max:10000'),

            ...$this->getTimestampsFields($request),
        ];
    }
}
