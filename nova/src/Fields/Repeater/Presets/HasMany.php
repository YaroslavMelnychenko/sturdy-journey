<?php

namespace Laravel\Nova\Fields\Repeater\Presets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany as EloquentHasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Fields\Repeater\RepeatableCollection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Support\Fluent;

class HasMany implements Preset
{
    /**
     * Save the field value to permanent storage.
     *
     * @param  string|null  $uniqueField
     * @return \Closure|void
     */
    public function set(
        NovaRequest $request,
        string $requestAttribute,
        Model $model,
        string $attribute,
        RepeatableCollection $repeatables,
        $uniqueField
    ) {
        return function () use ($request, $requestAttribute, $model, $attribute, $repeatables, $uniqueField) {
            $repeaterItems = collect($request->input($requestAttribute));
            /** @var EloquentHasMany $relation */
            $relation = $model->{$attribute}();

            if (! $uniqueField) {
                $relation->delete();
            } else {
                $this->deleteMissingRelations($attribute, $model, $repeaterItems, $uniqueField);
            }

            $repeaterItems->transform(function ($item, $blockKey) use ($request, $requestAttribute, $repeatables) {
                $block = $repeatables->findByKey($item['type']);
                $fields = FieldCollection::make($block->fields($request));
                $data = Fluent::make();

                $callbacks = $fields
                    ->withoutUnfillable()
                    ->withoutMissingValues()
                    ->map(function (Field $field) use ($request, $requestAttribute, $data, $blockKey) {
                        return $field->fillInto($request, $data, $field->attribute, "{$requestAttribute}.{$blockKey}.fields.{$field->attribute}");
                    })
                    ->filter(function ($callback) {
                        return is_callable($callback);
                    })->toBase();

                return [$data, $callbacks, $item];
            })->each(function ($tuple) use ($model, $relation, $uniqueField) {
                [$data, $callbacks, $row] = $tuple;

                if ($uniqueField) {
                    $this->upsertRelation($model, $data, $row, $uniqueField, $relation);
                } else {
                    $relation->forceCreate($data->getAttributes());
                }

                $callbacks->each->__invoke();
            });
        };
    }

    /**
     * Retrieve the value from storage and hydrate the field's value.
     *
     * @param  mixed  $model
     * @return \Illuminate\Support\Collection
     */
    public function get(NovaRequest $request, $model, string $attribute, RepeatableCollection $repeatables)
    {
        return RepeatableCollection::make($model->{$attribute})
            ->map(function ($block) use ($repeatables) {
                return $repeatables->newRepeatableByModel($block);
            });
    }

    /**
     * Delete missing relations.
     *
     * @param  mixed  $uniqueField
     */
    public function deleteMissingRelations(string $attribute, Model $model, Collection $repeaterItems, $uniqueField): void
    {
        $relation = $model->{$attribute}();

        $availableItems = $repeaterItems->map(function ($item) use ($uniqueField) {
            return $item['fields'][$uniqueField];
        })->all();

        $deletableIds = $relation->pluck($uniqueField)
            ->reject(function ($id) use ($availableItems) {
                return in_array($id, $availableItems);
            });

        if ($deletableIds->isNotEmpty()) {
            $relation->delete($deletableIds);
        }
    }

    /**
     * Upsert relation.
     *
     * @param  mixed  $uniqueField
     */
    public function upsertRelation(Model $model, Fluent $data, array $row, $uniqueField, EloquentHasMany $relation): void
    {
        $model->unguarded(function () use ($data, $row, $uniqueField, $relation) {
            $relation->updateOrCreate(
                [$uniqueField => $row['fields'][$uniqueField]],
                Arr::except($data->getAttributes(), $uniqueField)
            );
        });
    }
}
