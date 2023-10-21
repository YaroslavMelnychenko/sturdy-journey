<?php

namespace App\Traits;

trait Makeable
{
    public static function make(...$parameters)
    {
        return app()->make(get_called_class(), ...$parameters);
    }
}
