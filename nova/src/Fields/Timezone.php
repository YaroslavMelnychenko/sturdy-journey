<?php

namespace Laravel\Nova\Fields;

use DateTimeZone;

/**
 * @phpstan-type TOptionValue string
 * @phpstan-type TOptionLabel string
 */
class Timezone extends Select
{
    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->options(collect(DateTimeZone::listIdentifiers(DateTimeZone::ALL))->mapWithKeys(function ($timezone) {
            return [$timezone => $timezone];
        })->all());
    }
}
