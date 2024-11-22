<?php

namespace Database\Seeders;

use App\Models\ArticleScore;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserLikingArticleScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $scores = ArticleScore::all();

        foreach ($users as $user) {
            $counter = rand(3, 8);
            for ($i = 0; $i <= $counter; $i++) {
                $score = $scores->random();
                $user->likeArticleScore()->create([
                    'article_score_id' => $score->id,
                    'liking' => fake()->randomElement(['like', 'dislike']),
                ]);
            }
        }
    }
}
