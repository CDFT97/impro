<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
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
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'company' => $this->faker->company(),
            'type' => $type = rand(0,1),
            'discount' => $type == 1 ? rand(0, 50) : null
        ];
    }
}
