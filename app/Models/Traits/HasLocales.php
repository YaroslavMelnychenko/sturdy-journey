<?php

namespace App\Models\Traits;

trait HasLocales
{
    public function __(string $attribute): mixed
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            return $this->{$attribute};
        }

        return $this->{$attribute.'_'.$locale} ?? $this->{$attribute};
    }
}
