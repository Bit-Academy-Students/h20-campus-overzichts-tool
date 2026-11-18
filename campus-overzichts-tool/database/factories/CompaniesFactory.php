<?php

namespace Database\Factories;

use App\Models\Companies;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Companies>
 */
class CompaniesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'description' => fake()->optional(0.8)->paragraph(),
            'website' => fake()->optional(0.7)->url(),
            'email' => fake()->optional(0.8)->companyEmail(),
            'phone' => fake()->optional(0.7)->phoneNumber(),
            'address' => fake()->optional(0.8)->streetAddress(),
            'city' => fake()->optional(0.8)->city(),
        ];
    }
}
