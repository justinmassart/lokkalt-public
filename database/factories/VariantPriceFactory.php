<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VariantPrice>
 */
class VariantPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => rand(500, 10000) / 100,
            'currency' => fake()->randomElement(['EUR', 'USD', 'GBP']),
            'per' => fake()->randomElement(['unit', 'kg', 'g', 'L', 'pair', '2', '3', '4']),
        ];
    }
}
