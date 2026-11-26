<?php

namespace Database\Factories;

use App\Models\AssignmentClaim;
use App\Models\Assignment;
use App\Models\Contractor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AssignmentClaim>
 */
class AssignmentClaimFactory extends Factory
{
    public function definition(): array
    {
        return [
            'assignment_id' => Assignment::factory(),
            'user_id' => User::factory(),
            'contractor_id' => null,
            'status' => 'claimed',
            'claimed_at' => fake()->dateTimeBetween('-2 months', '-1 week'),
            'assigned_at' => null,
        ];
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'contractor_id' => Contractor::factory(),
            'status' => 'assigned',
            'assigned_at' => fake()->dateTimeBetween($attributes['claimed_at'], 'now'),
        ]);
    }

    public function completed(): static
    {
        return $this->assigned()->state(['status' => 'completed']);
    }
}
