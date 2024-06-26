<?php

namespace Laravel\Nova\Fields\Filters;

use Carbon\CarbonImmutable;
use Laravel\Nova\Http\Requests\NovaRequest;

class DateFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'date-field';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        $value = collect($value)->transform(function ($value) {
            return ! empty($value) ? rescue(function () use ($value) {
                return CarbonImmutable::createFromFormat('Y-m-d', $value);
            }, null) : null;
        });

        if ($value->filter()->isNotEmpty()) {
            if ($value[0] instanceof CarbonImmutable) {
                $value[0] = $value[0]->startOfDay();
            }

            if ($value[1] instanceof CarbonImmutable) {
                $value[1] = $value[1]->endOfDay();
            }

            $this->field->applyFilter($request, $query, $value->all());
        }

        return $query;
    }

    /**
     * Get the default options for the filter.
     *
     * @return array|mixed
     */
    public function default()
    {
        return [null, null];
    }
}
