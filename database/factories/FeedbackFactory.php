<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'name' => $this->faker->firstName.' '.$this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'short_description' => $this->faker->realText(rand(200, 2000)),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
