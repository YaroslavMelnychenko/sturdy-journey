<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Database\UniqueConstraintViolationException;
use Str;

use function Laravel\Prompts\search;

class CreateSlugsCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'app:slugs:generate {model} {column} {from}';

    protected $description = 'Command description';

    public function handle()
    {
        $model = $this->argument('model');
        $column = $this->argument('column');
        $from = $this->argument('from');

        try {
            $model = app("App\\Models\\$model");

            $items = $model::whereNull($column)->get();

            foreach ($items as $item) {
                try {
                    $item->$column = Str::slug($item->$from);
                    $item->save();
                } catch (UniqueConstraintViolationException $e) {
                    $item->$column = Str::slug($item->$from).'-'.$item->id;
                    $item->save();
                }
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }

        $this->info('Slugs generated successfully.');
    }

    protected function promptForMissingArgumentsUsing()
    {
        return [
            'model' => fn () => search(
                label: 'What model should be used for slugs?',
                options: fn () => ['Admin', 'Category', 'Feedback', 'Item']
            ),
            'column' => ['What column should be used for slugs?', 'slug'],
            'from' => ['What column should be to generate slugs?', 'name'],
        ];
    }
}
