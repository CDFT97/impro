<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materials = ["Vinil", "Fibra de carbono", "Papel", "papel aluminio"];
        $meters = [50,100,150,200,250,300,350];
        return [
            "name" => $this->faker->regexify('[A-Z]{5}[0-4]{3}'),
            "material" => $materials[array_rand($materials)],
            "stock_meters" => $m = $meters[array_rand($meters)],
            // "stock_quantity" => $m / 50,
            "unit_price_in_dollars" => $this->faker->randomFloat(1, 2, 15),
            "description" => $this->faker->text(15),
        ];
    }
}
