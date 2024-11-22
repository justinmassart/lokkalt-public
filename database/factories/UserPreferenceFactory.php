<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPreference>
 */
class UserPreferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $preferences = [
            'emails' => [
                'allow' => true,
                'shop' => [
                    'allow' => true,
                    'add_article' => true,
                    'lower_price' => true,
                    'add_event' => false,
                ],
                'favourite' => [
                    'allow' => true,
                    'delete' => false,
                    'update' => true,
                ],
            ],
            'sms' => [
                'allow' => true,
                'order' => [
                    'allow' => true,
                    'success' => true,
                    'delivery' => false,
                    'problem' => true,
                ],
            ],
        ];

        return [
            'preferences' => json_encode($preferences),
        ];
    }
}
