<?php

namespace App\Nova\Metrics\Progress;

use Error;
use Exception;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Progress;

class UsedDiskSpaceProgress extends Progress
{
    public function name()
    {
        return __('Used disk space');
    }

    public function calculate(NovaRequest $request)
    {
        try {
            $disk_info = shell_exec('df /');
            $lines = explode("\n", trim($disk_info));
            $data = explode(' ', preg_replace("/\s+/", ' ', $lines[1]));

            $total_space = $data[1];
            $used_space = $data[2];

            $counting = round($used_space / $total_space * 100, 2);
        } catch (Exception|Error $e) {
            $counting = 0;
        }

        return $this->result($counting, 100)->avoid();
    }

    public function cacheFor()
    {
        return now()->addSeconds(30);
    }

    public function uriKey()
    {
        return 'used-disk-space-progress';
    }
}
