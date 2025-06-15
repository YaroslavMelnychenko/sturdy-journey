<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paragraphs_count = rand(1, 3);

        $description = [];

        for ($i = 0; $i < $paragraphs_count; $i++) {
            $heading = $this->faker->sentence(rand(2, 8));
            $text = $this->faker->paragraph(rand(2, 6));

            $description[] = [
                'type' => 'key-value',
                'fields' => [
                    'heading' => $heading,
                    'text' => $text,
                ],
            ];
        }

        $features = [];

        $features_count = rand(1, 3);

        for ($i = 0; $i < $features_count; $i++) {
            $heading = $this->faker->sentence(rand(1, 3));
            $text = $this->faker->sentence(rand(2, 8));

            $features[] = [
                'type' => 'key-value',
                'fields' => [
                    'heading' => $heading,
                    'text' => $text,
                ],
            ];
        }

        return [
            'name' => $this->faker->company,
            'place' => $this->faker->city,
            'rating' => rand(20, 50) / 10,
            'short_description' => $this->faker->realText(rand(50, 500)),
            'link' => $this->faker->url,
            'description' => $description,
            'features' => $features,
            'icon' => 'items/icons/example.png'
        ];
    }
}
