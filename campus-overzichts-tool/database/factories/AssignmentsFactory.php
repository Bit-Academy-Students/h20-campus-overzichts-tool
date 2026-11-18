<?php

namespace Database\Factories;

use App\Models\Assignments;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignments>
 */
class AssignmentsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => rtrim(fake()->sentence(fake()->numberBetween(3, 7)), '.'),
            'description' => fake()->optional(0.9)->paragraphs(2, true),
            'source_url' => fake()->unique()->url(),
            'posted_at' => fake()->optional(0.9)->dateTimeBetween('-3 months')?->format('Y-m-d'),
            'scraped_at' => now(),
        ];
    }
}
