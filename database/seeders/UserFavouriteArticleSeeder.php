<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserFavouriteArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $articles = Article::all();

        foreach ($users as $user) {
            $counter = rand(7, 15);
            for ($i = 0; $i < $counter; $i++) {
                $article = $articles->random();
                $user->user_favourite_articles()->firstOrCreate([
                    'article_id' => $article->id,
                ]);
            }
        }
    }
}
