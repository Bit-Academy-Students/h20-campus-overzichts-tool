<?php

namespace Database\Factories;

use App\Models\Assignment_Claims;
use App\Models\Assignments;
use App\Models\Companies;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment_Claims>
 */
class Assignment_ClaimsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'assignment_id' => Assignments::factory(),
            'user_id' => User::factory(),
            'company_id' => null,
            'status' => 'claimed',
            'claimed_at' => fake()->dateTimeBetween('-2 months', '-1 week'),
            'assigned_at' => null,
        ];
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => Companies::factory(),
            'status' => 'assigned',
            'assigned_at' => fake()->dateTimeBetween($attributes['claimed_at'], 'now'),
        ]);
    }

    public function completed(): static
    {
        return $this->assigned()->state(['status' => 'completed']);
    }
}
