<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleScore;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $articles = Article::all();

        foreach ($articles as $article) {
            $random_score_count = rand(12, 25);
            for ($i = 0; $i <= $random_score_count; $i++) {
                $user = $users->random();
                $score = ArticleScore::factory()->create([
                    'article_id' => $article->id,
                    'user_id' => $user->id,
                ]);
                $counter = rand(12, 25);
                for ($i = 0; $i <= $counter; $i++) {
                    $u = User::where('id', '!=', $user->id)->get()->random();
                    $u->likeArticleScore()->create([
                        'article_score_id' => $score->id,
                        'liking' => fake()->randomElement(['like', 'dislike']),
                    ]);
                }
            }
        }

        /* foreach ($users as $user) {
            $articles = Article::all();

            for ($i = 0; $i < 3; $i++) {
                $article = $articles->random();
                $score = Score::factory()->create([
                    'user_id' => $user->id,
                ]);
                $article->article_scores()->create([
                    'score_id' => $score->id,
                ]);
            }
        } */
    }
}
