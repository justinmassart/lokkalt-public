<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleGlobalScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::all();

        foreach ($articles as $article) {
            $article->global_score()->create([
                'score' => rand(100, 500) / 100,
                'total_votes' => rand(1, 500),
            ]);
        }
    }
}
