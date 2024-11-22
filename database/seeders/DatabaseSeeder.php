<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // USERS
        $this->call(UserSeeder::class);
        $this->call(UserPreferenceSeeder::class);
        $this->call(UserNotificationSeeder::class);

        // CATEGORIES
        //$this->call(CategorySeeder::class);
        //$this->call(SubCategorySeeder::class);
        $category = File::get(database_path('/seeders/category.sql'));
        DB::unprepared($category);

        // SHOP
        $this->call(ShopSeeder::class);

        // ARTICLES
        // $this->call(ArticleSeeder::class); THIS IS NOT NEEDED SINCE IT IS CALLED IN THE SHOPSEEDER
        // $this->call(ArticleImageSeeder::class); TESTING AWS S3
        $this->call(VariantSeeder::class);
        $this->call(ArticleQuestionSeeder::class);
        //$this->call(ArticleCartSeeder::class);
        $this->call(ArticleScoreSeeder::class);
        $this->call(ArticleScoreAnswerSeeder::class);
        $this->call(ArticleGlobalScoreSeeder::class);

        // STOCK
        $this->call(StockSeeder::class);

        // ORDER
        /* $this->call(OrderSeeder::class); */

        // USER
        $this->call(UserFavouriteArticleSeeder::class);
        $this->call(UserFavouriteShopSeeder::class);
        // $this->call(UserLikingScoreSeeder::class);

        // PACK
        //$this->call(PackSeeder::class);

        $pack = File::get(database_path('/seeders/pack.sql'));
        DB::unprepared($pack);

        $this->call(ExamPackSeeder::class);

        // SQL
        $exam = File::get(database_path('/seeders/exam.sql'));
        DB::unprepared($exam);
    }
}
