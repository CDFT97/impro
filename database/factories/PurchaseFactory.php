<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => rand(1,30),
            'product_id' => null,
            'dolar_price' => $dolar_price = $this->faker->randomFloat(2, 30, 40),
            'amount_usd' => $amount = $this->faker->randomFloat(2, 15, 300),
            'amount' => $amount * $dolar_price,
            'date' => $this->faker->dateTimeBetween('-2 week', '+1 days'),
            'description' => $this->faker->sentence(rand(3,6)),
            'quantity_meters' => $this->faker->randomDigitNotNull(),
        ];
    }
}
