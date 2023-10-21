<?php

namespace App\Nova\Metrics\Value;

use App\Models;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class NewFeedbackValue extends Value
{
    public function name()
    {
        return __('New feedback');
    }

    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Models\Feedback::class);
    }

    public function ranges()
    {
        return [
            'TODAY' => Nova::__('Today'),
            'YESTERDAY' => Nova::__('Yesterday'),
            7 => Nova::__('7 Days'),
            14 => Nova::__('14 Days'),
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
            365 => Nova::__('365 Days'),
        ];
    }

    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
}
