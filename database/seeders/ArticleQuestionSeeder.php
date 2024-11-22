<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = Article::all();
        $users = User::where('role', 'user')->get();

        foreach ($articles as $article) {
            $counter = rand(1, 5);
            for ($i = 0; $i <= $counter; $i++) {
                $user = $users->random();
                $question = Question::factory()->create([
                    'user_id' => $user->id,
                ]);
                $article->article_questions()->create([
                    'question_id' => $question->id,
                ]);
                $question->articleAnswer()->create([
                    'answer' => fake()->text(200),
                    'question_id' => $question->id,
                    'shop_id' => $article->shops()->first()->id,
                    'user_id' => $article->shops()->first()->owners()->first()->id,
                ]);
            }
        }
    }
}
