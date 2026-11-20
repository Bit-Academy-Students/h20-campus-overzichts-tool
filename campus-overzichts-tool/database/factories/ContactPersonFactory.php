<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ContactPerson;
use App\Models\Contractor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactPerson>
 */
class ContactPersonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'contractor_id' => null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'job_title' => fake()->optional(0.7)->jobTitle(),
            'email' => fake()->optional(0.9)->safeEmail(),
            'phone' => fake()->optional(0.7)->phoneNumber(),
        ];
    }

    public function forContractor(?Contractor $contractor = null): static
    {
        return $this->state(fn () => [
            'client_id' => null,
            'contractor_id' => $contractor?->id ?? Contractor::factory(),
        ]);
    }
}
