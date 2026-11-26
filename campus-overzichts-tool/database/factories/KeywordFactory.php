<?php

namespace Database\Factories;

use App\Models\Keywords;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Keywords>
 */
class KeywordsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'word' => fake()->unique()->word(),
        ];
    }
}
