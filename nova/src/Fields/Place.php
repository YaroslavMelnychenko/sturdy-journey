<?php

namespace Laravel\Nova\Fields;

/**
 * @deprecated Places API will stop functioning on May 31st, 2022
 */
class Place extends Text
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'place-field';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|\Closure|callable|object|null  $attribute
     * @param  (callable(mixed, mixed, ?string):(mixed))|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->secondAddressLine('address_line_2')
            ->city('city')
            ->state('state')
            ->postalCode('postal_code')
            ->suburb('suburb')
            ->country('country')
            ->latitude('latitude')
            ->longitude('longitude');
    }

    /**
     * Instruct the field to only display cities in its results.
     *
     * @return $this
     */
    public function onlyCities()
    {
        return $this->type('city');
    }

    /**
     * Set the place type.
     *
     * @param  string  $type
     * @return $this
     */
    public function type($type)
    {
        if ($type == 'city') {
            $this->secondAddressLine(null)->city(null)->postalCode(null);
        }

        return $this->withMeta(['placeType' => $type]);
    }

    /**
     * Set the countries to search within.
     *
     * @return $this
     */
    public function countries(array $countries)
    {
        return $this->withMeta(['countries' => $countries]);
    }

    /**
     * Specify the field that contains the second address line.
     *
     * @param  string|null  $field
     * @return $this
     */
    public function secondAddressLine($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the field that contains the city.
     *
     * @param  string|null  $field
     * @return $this
     */
    public function city($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the field that contains the state.
     *
     * @param  string  $field
     * @return $this
     */
    public function state($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the field that contains the postal code.
     *
     * @param  string|null  $field
     * @return $this
     */
    public function postalCode($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the field that contains the suburb.
     *
     * @param  string  $field
     * @return $this
     */
    public function suburb($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the field that contains the country.
     *
     * @param  string  $field
     * @return $this
     */
    public function country($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the field that contains the latitude.
     *
     * @param  string  $field
     * @return $this
     */
    public function latitude($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Specify the language that places.js should use.
     *
     * @param  string  $language
     * @return $this
     */
    public function language($language)
    {
        return $this->withMeta([__FUNCTION__ => $language]);
    }

    /**
     * Specify the field that contains the longitude.
     *
     * @param  string  $field
     * @return $this
     */
    public function longitude($field)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    /**
     * Register depends on to a field.
     *
     * @param  string|array  $attributes
     * @param  callable|string  $mixin
     * @return $this
     */
    public function dependsOn($attributes, $mixin)
    {
        throw new \Exception('The `dependsOn` option is not available on Place fields.');
    }
}
