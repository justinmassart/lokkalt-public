<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Variant>
 */
class VariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();
        $details = ['weight' => '100g', 'color' => 'blue'];

        $random_tags_count = rand(1, 3);
        /*         $tags = [];
        for ($i = 0; $i < $random_tags_count; $i++) {
            $tags[] = fake()->word();
        } */

        $is_visible = fake()->boolean() ? true : false;

        return [
            'reference' => str()->random(14),
            'name' => $name,
            'slug' => str()->slug($name),
            'description' => fake()->text(320),
            'details' => $details,
            'is_visible' => true,
        ];
    }
}
