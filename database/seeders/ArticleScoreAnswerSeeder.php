<?php

namespace Database\Seeders;

use App\Models\ArticleScore;
use App\Models\ArticleScoreAnswer;
use Illuminate\Database\Seeder;

class ArticleScoreAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articlesScores = ArticleScore::all();

        foreach ($articlesScores as $articleScore) {
            $chance = fake()->boolean();
            if ($chance) {
                $article = $articleScore->article;
                $score = $articleScore->score;
                $shop = $article->shops()->first();
                $shopOwner = $shop->owners()->first();

                ArticleScoreAnswer::create([
                    'answer' => fake()->text(100),
                    'article_score_id' => $articleScore->id,
                    'shop_id' => $shop->id,
                    'user_id' => $shopOwner->id,
                ]);
            }
        }
    }
}
