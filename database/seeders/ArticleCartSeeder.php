<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleCartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $articles = Article::all();

        foreach ($users as $user) {
            $cart = $user->cart()->create();
            $counter = rand(1, 3);
            for ($i = 0; $i < $counter; $i++) {
                $article = $articles->random();
                $quantity = rand(1, 5);
                $cart->article_carts()->create([
                    'quantity' => $quantity,
                    'article_id' => $article->id,
                ]);
            }
        }
    }
}
