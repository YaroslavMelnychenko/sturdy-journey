<?php

namespace Laravel\Nova\Fields;

use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Support\UndefinedValue;

/**
 * @property array $fieldDependencies
 */
trait DependentFields
{
    /**
     * Determine of should emit change event.
     *
     * @var bool
     */
    protected $dependentShouldEmitChangesEvent = false;

    /**
     * Resolve the dependent component key.
     *
     * @return string
     */
    public function dependentComponentKey()
    {
        return sprintf('%s.%s.%s', Str::slug(class_basename(get_called_class())), $this->component, $this->attribute);
    }

    /**
     * Resolve dependent field value.
     *
     * @return mixed
     */
    public function resolveDependentValue(NovaRequest $request)
    {
        return $this->value ?? $this->resolveDefaultValue($request);
    }

    /**
     * Sync depends on logic.
     *
     * @return $this
     */
    public function syncDependsOn(NovaRequest $request)
    {
        $this->value = new UndefinedValue();
        $this->defaultCallback = function () {
            return new UndefinedValue();
        };

        $this->applyDependsOn($request);

        $value = with($this->value, function ($value) use ($request) {
            if ($value instanceof UndefinedValue && $this->requestShouldResolveDefaultValue($request)) {
                $this->value = null;

                return $this->resolveDefaultValue($request);
            }

            return $value;
        });

        $this->dependentShouldEmitChangesEvent = ! $value instanceof UndefinedValue;

        if ($value instanceof UndefinedValue) {
            $this->value = null;
        } else {
            $this->value = ! is_null($value) ? $value : '';
        }

        return $this;
    }

    /**
     * Apply depends on logic.
     *
     * @return $this
     */
    public function applyDependsOn(NovaRequest $request)
    {
        $this->fieldDependencies = collect($this->fieldDependencies ?? [])
            ->map(function (Dependent $dependent) use ($request) {
                return $dependent->handle($this, $request);
            })->all();

        return $this;
    }

    /**
     * Get depends on attributes.
     *
     * @return array<string, mixed>|null
     */
    protected function getDependentsAttributes(NovaRequest $request)
    {
        /** @var \Illuminate\Support\Collection<string, mixed> $attributes */
        $attributes = collect($this->fieldDependencies ?? [])->map(function (Dependent $dependent) {
            return $dependent->getAttributes();
        })->collapse();

        if ($attributes->isNotEmpty()) {
            return $attributes->all();
        }

        return null;
    }

    /**
     * Serialize dependent field.
     *
     * @return array<string, mixed>
     */
    protected function serializeDependentField(NovaRequest $request): array
    {
        return [
            'dependentComponentKey' => $this->dependentComponentKey(),
            'dependsOn' => $this->getDependentsAttributes($request),
            'dependentShouldEmitChangesEvent' => $this->dependentShouldEmitChangesEvent,
        ];
    }
}
