<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'last_name' => $this->faker->lastName(),
            'ci' => rand(10000000, 30000000),
            'rif' => "J-".mt_rand(10000000, 99999999),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'email' => $this->faker->email(),
            'description' => $this->faker->sentence(rand(3,6)),
            'company' => $this->faker->company(),
        ];
    }
}
