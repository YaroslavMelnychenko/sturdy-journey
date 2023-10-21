<?php

namespace App\Nova\Storage;

use Laravel\Nova\Http\Requests\NovaRequest;
use Str;

class BaseStorage
{
    public function __construct(
        private ?string $folder_name = null
    ) {
        //
    }

    public static function folder(string $folder_name): self
    {
        return new static($folder_name);
    }

    public function __invoke(NovaRequest $request, $model, $attribute, $request_attribute, $disk, $storage_path)
    {
        $folder_name = $this->folder_name ?? Str::plural(Str::kebab(class_basename($model)));

        $file = $request->{$attribute};

        return [
            $attribute => $file->storePublicly($folder_name, $disk),
        ];
    }
}
