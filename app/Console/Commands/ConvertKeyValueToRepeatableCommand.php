<?php

namespace App\Console\Commands;

use App\Models;
use Illuminate\Console\Command;

class ConvertKeyValueToRepeatableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:key-value-to-repeatable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $items = Models\Item::all();

        /*

        [
            {
                "type": "key-value",
                "fields": {
                    "text": "TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest",
                    "heading": "2. Plus"
                }
            },
            {
                "type": "key-value",
                "fields": {
                    "text": "Test",
                    "heading": "1. Minus"
                }
            }
        ]

        */

        foreach ($items as $item) {
            $description = [];
            $features = [];
            $seo = [];

            if ($item->description !== null) {
                foreach ($item->description as $key => $value) {
                    $description[] = [
                        'type' => 'key-value',
                        'fields' => [
                            'text' => $value,
                            'heading' => $key,
                        ],
                    ];
                }
            }

            if ($item->description !== null) {
                foreach ($item->features as $key => $value) {
                    $features[] = [
                        'type' => 'key-value',
                        'fields' => [
                            'text' => $value,
                            'heading' => $key,
                        ],
                    ];
                }
            }

            if ($item->seo !== null) {
                foreach ($item->seo as $key => $value) {
                    $seo[] = [
                        'type' => 'key-value',
                        'fields' => [
                            'text' => $value,
                            'heading' => $key,
                        ],
                    ];
                }
            }

            $item->description = $description;
            $item->features = $features;
            $item->seo = $seo;
            $item->save();
        }

        return $this->info('Done');
    }
}
