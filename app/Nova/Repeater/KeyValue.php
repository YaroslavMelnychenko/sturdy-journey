<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields;
use Laravel\Nova\Http\Requests\NovaRequest;

class KeyValue extends Fields\Repeater\Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Fields\Text::make(__('Heading'))
                ->rules('required'),

            Fields\Textarea::make(__('Text'))
                ->rules('required'),
        ];
    }
}
