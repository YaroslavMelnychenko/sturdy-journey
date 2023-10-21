<?php

namespace Database\Seeders;

use App\Models;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Models\Category::inRandomOrder()->get();

        foreach ($categories as $category) {
            $items_count = rand(9, 20);

            for ($i = 0; $i < $items_count; $i++) {
                Models\Item::factory()->create([
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
