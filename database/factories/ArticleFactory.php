<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->word();

        $slug = str()->slug($name);

        /* $random_tags_count = rand(1, 3); */
        /*         $tags = [];
        for ($i = 0; $i < $random_tags_count; $i++) {
            $tags[] = fake()->word();
        } */

        $details = ['weight' => '100g', 'color' => 'blue'];

        return [
            'reference' => str()->random(12),
            'is_active' => true,
            'name' => $name,
            'slug' => $slug,
            /* 'tags' => $tags, */
            'description' => fake()->text(320),
            'details' => $details,
        ];
    }
}
