<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tours>
 */
class ToursFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'start_date' => now(),
            'end_date' => now()->addDays(rand(1, 10)),
            'price' => fake()->randomFloat(2, 10, 999)
        ];
    }
}
