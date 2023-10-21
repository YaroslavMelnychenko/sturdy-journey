<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    public function name()
    {
        return __('Metrics');
    }

    public function cards()
    {
        return [
            Metrics\Trend\FeedbackCountTrend::make()->width('1/3'),
            Metrics\Value\NewFeedbackValue::make()->width('1/3'),
            Metrics\Progress\UsedDiskSpaceProgress::make()->width('1/3'),
        ];
    }
}
