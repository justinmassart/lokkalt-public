<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'food',
            'beauty-and-care',
            'jewelry',
            'decorations',
            'electronics',
            'computers',
            'video-games',
            'toys',
            'books',
            'home-and-garden',
            'customized-products',
            'sports-and-leisure',
            'alcohol',
        ];

        sort($categories);

        foreach ($categories as $category) {
            Category::factory()->create([
                'name' => $category,
                'slug' => Str::slug($category),
                'image' => 'img/categories/category.jpg',
            ]);
        }
    }
}
