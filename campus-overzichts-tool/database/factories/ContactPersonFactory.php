<?php

namespace Database\Factories;

use App\Models\Contactpersoon;
use App\Models\Opdrachtgever;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contactpersoon>
 */
class ContactpersoonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'opdrachtgever_id' => Opdrachtgever::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'function' => fake()->optional(0.7)->jobTitle(),
            'email' => fake()->optional(0.9)->safeEmail(),
            'phone' => fake()->optional(0.7)->phoneNumber(),
        ];
    }
}
