<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ArticleImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::all();

        foreach ($articles as $article) {
            for ($i = 0; $i < 4; $i++) {
                $image = Image::factory()->create([
                    'url' => 'img/articles/article.jpg',
                ]);
                $article->article_images()->create([
                    'image_id' => $image->id,
                ]);
            }
        }
    }
}
