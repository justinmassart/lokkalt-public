<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $countries = ['BE', 'NL', 'FR', 'DE', 'LU'];
        sort($countries);

        $name = fake()->company();
        $country = fake()->randomElement($countries);
        $address = fake()->streetAddress();
        $postalCode = rand(1000, 9992);
        $city = fake()->city();
        $countryAddress = fake()->country();
        $fullAddress = "$address, $postalCode $city, $countryAddress";
        $opening_hours = [
            'monday' => [
                [
                    'from' => '08:00',
                    'to' => '12:30',
                ],
                [
                    'from' => '13:30',
                    'to' => '18:00',
                ],
            ],
            'tuesday' => [
                [
                    'from' => '09:00',
                    'to' => '13:30',
                ],
                [
                    'from' => '14:30',
                    'to' => '19:00',
                ],
            ],
            'wednesday' => [
                [
                    'from' => '08:30',
                    'to' => '13:00',
                ],
            ],
            'thursday' => [
                [
                    'from' => '08:00',
                    'to' => '12:30',
                ],
                [
                    'from' => '13:30',
                    'to' => '18:00',
                ],
            ],
            'friday' => [
                [
                    'from' => '09:00',
                    'to' => '13:30',
                ],
                [
                    'from' => '14:30',
                    'to' => '19:00',
                ],
            ],
            'saturday' => [
                [
                    'from' => '08:30',
                    'to' => '13:00',
                ],
            ],
            'sunday' => [],
        ];
        $slug = Str::slug($country.'-'.$postalCode.'-'.$name);

        return [
            'is_active' => true,
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'description' => fake()->text(300),
            'country' => $country,
            'city' => fake()->city(),
            'postal_code' => $postalCode,
            'address' => $fullAddress,
            'bank_account' => str()->upper($country.rand(10000000000000, 99999999999999)),
            'opening_hours' => $opening_hours,
            'slug' => $slug,
        ];
    }
}
