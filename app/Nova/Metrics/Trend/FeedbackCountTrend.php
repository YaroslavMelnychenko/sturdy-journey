<?php

namespace App\Nova\Metrics\Trend;

use App\Models;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Nova;

class FeedbackCountTrend extends Trend
{
    public function name()
    {
        return __('Feedback count trend');
    }

    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Models\Feedback::class)->showSumValue();
    }

    public function ranges()
    {
        return [
            1 => Nova::__('1 day'),
            2 => Nova::__('2 days'),
            7 => Nova::__('7 days'),
            14 => Nova::__('14 days'),
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
            90 => Nova::__('90 Days'),
        ];
    }

    public function cacheFor()
    {
        return now()->addSeconds(30);
    }

    public function uriKey()
    {
        return 'feedback-count-trend';
    }
}
