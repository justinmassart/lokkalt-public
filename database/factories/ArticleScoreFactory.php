<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleScore>
 */
class ArticleScoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'score' => rand(100, 500) / 100,
            'comment' => fake()->boolean() ? fake()->text(100) : null,
            'user_id' => User::whereRole('user')->get()->random()->id,
        ];
    }
}
