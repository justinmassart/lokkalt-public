<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleScore;
use App\Models\ArticleScoreAnswer;
use App\Models\Category;
use App\Models\Image;
use App\Models\Question;
use App\Models\Score;
use App\Models\Shop;
use App\Models\User;
use App\Models\Variant;
use App\Models\VariantPrice;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(UserPreferenceSeeder::class);
        $this->call(UserNotificationSeeder::class);

        $this->call(CategorySeeder::class);
        $this->call(SubCategorySeeder::class);

        $countries = ['BE', 'FR', 'LU', 'DE', 'NL'];

        foreach ($countries as $country) {
            Shop::factory()->count(2)->create([
                'country' => $country,
            ]);
        }

        $shops = Shop::all();

        foreach ($shops as $shop) {
            // IMAGES

            $images = Image::factory()->count(4)->create();

            foreach ($images as $image) {
                $shop->shop_images()->create([
                    'image_id' => $image->id,
                ]);
            }

            // ARTICLES

            $random_article_count = rand(2, 5);

            for ($i = 0; $i <= $random_article_count; $i++) {
                $category = Category::all()->random();
                $sub_category_id = $category->sub_categories()->get()->random()->id;
                $article_name = fake()->unique()->words(2, true);
                $article = Article::factory()->create([
                    'name' => $article_name,
                    'slug' => str()->slug($article_name),
                    'shop_id' => $shop->id,
                    'category_id' => $category->id,
                    'sub_category_id' => $sub_category_id,
                ]);
                $images = Image::factory()->count(4)->create()->each(function ($image, $index) {
                    if ($index === 0) {
                        $image->is_main_image = true;
                        $image->save();
                    }
                });
                foreach ($images as $image) {
                    $article->article_images()->create([
                        'image_id' => $image->id,
                    ]);
                }
            }

            // SCORES

            $random_score_count = rand(5, 10);

            for ($i = 0; $i < $random_score_count; $i++) {
                $score = Score::factory()->create();
                $shop->shop_scores()->create([
                    'score_id' => $score->id,
                ]);
            }

            // GLOBAL SCORE

            $scores_count = $shop->scores()->count();
            $scores_sum = $shop->scores()->sum('rating');

            $shop->global_score()->create([
                'score' => number_format($scores_sum / $scores_count, 2),
                'total_votes' => $scores_count,
            ]);

            // OWNERS

            $random_owner_count = rand(1, 2);

            for ($i = 0; $i < $random_owner_count; $i++) {
                $owner = User::factory()->create([
                    'role' => 'seller',
                ]);
                $shop->shopOwners()->create([
                    'user_id' => $owner->id,
                ]);
            }

            // EMPLOYEES
            $random_employee_count = rand(1, 3);

            for ($i = 0; $i < $random_employee_count; $i++) {
                $employee = User::factory()->create([
                    'role' => 'employee',
                ]);
                $shop->shop_employees()->create([
                    'user_id' => $employee->id,
                ]);
            }
        }

        $articles = Article::all();

        foreach ($articles as $article) {
            $counter = rand(1, 3);
            for ($i = 0; $i <= $counter; $i++) {
                // variant
                $variant_name = fake()->unique()->words(2, true);
                $variant = Variant::factory()->create([
                    'name' => $variant_name,
                    'slug' => str()->slug($variant_name),
                    'article_id' => $article->id,
                ]);
            }
        }

        $variants = Variant::all();

        foreach ($variants as $variant) {
            $currencies = ['EUR', 'USD', 'GBP'];

            foreach ($currencies as $currency) {
                VariantPrice::factory()->create([
                    'currency' => $currency,
                    'variant_id' => $variant->id,
                    'per' => fake()->randomElement(['unit', 'kg', 'g', 'L', 'pair', '2', '3', '4']),
                ]);
            }
        }

        $articles = Article::all();
        $users = User::where('role', 'user')->get();

        foreach ($articles as $article) {
            $counter = rand(1, 3);
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

        $users = User::where('role', 'user')->get();
        $articles = Article::all();

        foreach ($articles as $article) {
            $random_score_count = rand(5, 10);
            for ($i = 0; $i <= $random_score_count; $i++) {
                $user = $users->random();
                $score = Score::factory()->create([
                    'user_id' => $user->id,
                ]);
                $articleScore = ArticleScore::create([
                    'article_id' => $article->id,
                    'score_id' => $score->id,
                ]);
                $counter = rand(25, 50);
                for ($i = 0; $i <= $counter; $i++) {
                    $u = User::where('id', '!=', $user->id)->get()->random();
                    $u->liking_scores()->create([
                        'score_id' => $score->id,
                        'liking' => fake()->randomElement(['like', 'dislike']),
                    ]);
                }
            }
        }

        $articlesScores = ArticleScore::all();

        foreach ($articlesScores as $articleScore) {
            $chance = fake()->boolean();
            if ($chance) {
                $article = $articleScore->article;
                $score = $articleScore->score;
                $shop = $article->shop;
                $shopOwner = $shop->owners()->first();

                ArticleScoreAnswer::create([
                    'answer' => fake()->text(200),
                    'score_id' => $score->id,
                    'shop_id' => $shop->id,
                    'user_id' => $shopOwner->id,
                ]);
            }
        }

        $articles = Article::all();

        foreach ($articles as $article) {
            $article->global_score()->create([
                'score' => rand(100, 500) / 100,
                'total_votes' => rand(1, 500),
            ]);
        }

        $variants = Variant::all();

        foreach ($variants as $variant) {
            $quantity = rand(0, 10);
            $status = 'out';

            if ($quantity >= 5) {
                $status = 'in';
            } elseif ($quantity < 5 && $quantity > 0) {
                $status = 'limited';
            } elseif ($quantity === 0) {
                $status = 'out';
            }

            $stock = $variant->stock()->create([
                'quantity' => $quantity,
                'status' => $status,
                'article_id' => $variant->article->id,
            ]);

            $variant->stockOperations()->create([
                'stock_before' => 0,
                'stock_after' => $stock->quantity,
                'operation' => '+'.$stock->quantity,
                'comment' => fake()->text(35),
                'stock_id' => $stock->id,
                'user_id' => $variant->article->shops()->first()->owners->random()->id,
            ]);
        }

        $users = User::where('role', 'user')->get();
        $articles = Article::all();

        foreach ($users as $user) {
            $counter = rand(1, 5);
            for ($i = 0; $i < $counter; $i++) {
                $article = $articles->random();
                $user->user_favourite_articles()->create([
                    'article_id' => $article->id,
                ]);
            }
        }
    }
}
