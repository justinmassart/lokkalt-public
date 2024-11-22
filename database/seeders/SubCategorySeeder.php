<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_categories = [
            'food' => ['fruits', 'vegetables', 'dairy'],
            'beauty-and-care' => ['skincare', 'haircare', 'makeup'],
            'jewelry' => ['necklaces', 'earrings', 'bracelets'],
            'decorations' => ['paintings', 'vases', 'candles'],
            'electronics' => ['smartphones', 'laptops', 'headphones'],
            'computers' => ['desktops', 'laptops', 'accessories'],
            'video-games' => ['action', 'adventure', 'puzzle'],
            'toys' => ['dolls', 'cars', 'building-blocks'],
            'books' => ['fiction', 'non-fiction', 'poetry'],
            'home-and-garden' => ['furniture', 'plants', 'outdoor-decor'],
            'customized-products' => ['personalized-gifts', 'custom-apparel', 'engraved-items'],
            'sports-and-leisure' => ['outdoor-gear', 'fitness-equipment', 'camping-supplies'],
            'alcohol' => ['wine', 'beer', 'spirits'],
        ];

        foreach ($sub_categories as $category => $sub_category) {
            foreach ($sub_category as $sub) {
                SubCategory::factory()->create([
                    'name' => $sub,
                    'slug' => Str::slug($sub),
                    'image' => 'img/categories/category.jpg',
                    'category_id' => Category::where('name', $category)->first()->id,
                ]);
            }
        }
    }
}
