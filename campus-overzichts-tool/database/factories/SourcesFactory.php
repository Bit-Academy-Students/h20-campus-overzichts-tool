<?php

namespace Database\Factories;

use App\Models\Sources;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sources>
 */
class SourcesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'url' => fake()->unique()->url(),
            'active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }
}
