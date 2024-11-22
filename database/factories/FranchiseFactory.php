<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Franchise>
 */
class FranchiseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = ['BE', 'FR', 'LU', 'DE', 'NL'];

        $country = fake()->randomElement($countries);

        return [
            'name' => fake()->company(),
            'email' => fake()->email(),
            'phone' => null,
            'VAT' => str()->upper($country.rand(1000, 9999).rand(100, 999).rand(100, 999)),
            'bank_account' => str()->upper($country.rand(1000, 9999).rand(100, 999).rand(100, 999)),
            'country' => $country,
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'address' => fake()->address(),
        ];
    }
}
