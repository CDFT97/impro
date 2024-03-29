<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "client_id" => $this->faker->numberBetween(1, 11),
            "amount" => 0,
            "hash" => $this->faker->uuid(),
            "status" => Order::STATUS["Pending"],
            'description' => $this->faker->sentence(3),
        ];
    }
}
