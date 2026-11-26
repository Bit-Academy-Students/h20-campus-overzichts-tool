<?php

namespace Database\Factories;

use App\Models\Keyword;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Keyword>
 */
class KeywordFactory extends Factory
{
    public function definition(): array
    {
        return [
            'word' => fake()->unique()->word(),
        ];
    }
}
