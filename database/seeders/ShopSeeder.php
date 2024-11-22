<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Franchise;
use App\Models\Image;
use App\Models\Shop;
use App\Models\ShopArticle;
use App\Models\ShopScore;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = [];

        for ($i = 10; $i < 20; $i++) {
            $sellers[] = 'vendeur.' . $i + 1 . '@mail.com';
        }

        array_values($sellers);

        $countries = ['BE', 'LU'];

        foreach ($countries as $country) {
            for ($i = 0; $i < 10; $i++) {
                do {
                    $vat = str()->upper($country . rand(1000, 9999) . rand(100, 999) . rand(100, 999));
                } while (Franchise::where('VAT', $vat)->exists());

                $bank_account = fake()->boolean() === 1 ? str()->upper($country . rand(1000, 9999) . rand(100, 999) . rand(100, 999)) : null;

                $f = Franchise::factory()->create([
                    'verified_at' => now(),
                    'VAT' => $vat,
                    'bank_account' => $bank_account,
                    'country' => $country,
                    'stripe_customer_id' => '123457890',
                ]);
            }
        }

        $franchises = Franchise::all();

        foreach ($franchises as $franchise) {
            $randomShopCount = rand(1, 2);

            $franchiseUser = null;

            if (count($sellers) > 0) {
                $franchiseUser = User::factory()->create([
                    'role' => 'seller',
                    'email' => $sellers[0],
                ]);
                unset($sellers[0]);
                $sellers = array_values($sellers);
            } else {
                $franchiseUser = User::factory()->create([
                    'role' => 'seller',
                ]);
            }

            $franchise->franchiseOwner()->create([
                'user_id' => $franchiseUser->id,
            ]);

            $franchise->subscription()->create([
                'has_paid' => true,
                'customer_id' => rand(1000000000, 9999999999),
                'subscription_id' => rand(1000000000, 9999999999),
                'payment_id' => rand(1000000000, 9999999999),
                'stripe_status' => 'paid',
                'stripe_price' => 50,
            ]);

            for ($i = 0; $i < $randomShopCount; $i++) {
                do {
                    $postalCode = rand(1000, 9999);
                } while (Shop::whereSlug(str()->slug($franchise->country . '-' . $postalCode . '-' . $franchise->name))->exists());

                $shop = Shop::factory()->create([
                    'name' => $franchise->name,
                    'country' => $franchise->country,
                    'bank_account' => $franchise->bank_account ? null : str()->upper($franchise->country . rand(10000000000000, 99999999999999)),
                    'franchise_id' => $franchise->id,
                    'slug' => str()->slug($franchise->country . '-' . $postalCode . '-' . $franchise->name),
                ]);

                $shop->shopOwners()->create([
                    'user_id' => $franchiseUser->id,
                ]);
            }
        }

        $shops = Shop::all();

        foreach ($shops as $shop) {

            // ARTICLES

            $random_article_count = rand(2, 5);

            for ($i = 0; $i < $random_article_count; $i++) {
                $category = Category::all()->random();
                $sub_category_id = $category->sub_categories()->get()->random()->id;
                $article_name = fake()->unique()->words(2, true);
                $article = Article::factory()->create([
                    'name' => $article_name,
                    'slug' => str()->slug($article_name),
                    'category_id' => $category->id,
                    'sub_category_id' => $sub_category_id,
                ]);

                $vCounter = rand(1, 3);
                for ($j = 0; $j < $vCounter; $j++) {
                    // variant
                    $variant_name = fake()->words(2, true);
                    $variant = Variant::factory()->create([
                        'name' => $variant_name,
                        'slug' => str()->slug($variant_name),
                        'article_id' => $article->id,
                    ]);
                    $shopsOfFranchise = $shop->franchise->shops;
                    if (fake()->boolean()) {
                        foreach ($shopsOfFranchise as $s) {
                            ShopArticle::create([
                                'shop_id' => $s->id,
                                'article_id' => $article->id,
                                'variant_id' => $variant->id,
                            ]);
                        }
                    } else {
                        ShopArticle::create([
                            'shop_id' => $shop->id,
                            'article_id' => $article->id,
                            'variant_id' => $variant->id,
                        ]);
                    }
                }

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

            $random_score_count = rand(12, 25);

            for ($i = 0; $i < $random_score_count; $i++) {
                $score = ShopScore::factory()->create([
                    'shop_id' => $shop->id,
                ]);
            }

            // GLOBAL SCORE

            $scores_count = $shop->scores()->count();
            $scores_sum = $shop->scores()->sum('score');

            $shop->global_score()->create([
                'score' => number_format($scores_sum / $scores_count, 2),
                'total_votes' => $scores_count,
            ]);

            // OWNERS

            $random_owner_count = rand(1, 2);

            $newShop = Shop::factory()->create([
                'name' => $shop->name,
                'country' => $shop->country,
                'franchise_id' => $shop->franchise->id,
            ]);

            for ($i = 0; $i < $random_owner_count; $i++) {
                $owner = User::factory()->create([
                    'role' => 'seller',
                ]);
                $shop->shopOwners()->create([
                    'user_id' => $owner->id,
                ]);
                $newShop->shopOwners()->create([
                    'user_id' => $owner->id,
                ]);
                $newShop->shopOwners()->create([
                    'user_id' => $shop->franchise->owner->id,
                ]);
            }

            // EMPLOYEES
            /* $random_employee_count = rand(1, 3);

            for ($i = 0; $i < $random_employee_count; $i++) {
                $employee = User::factory()->create([
                    'role' => 'employee',
                ]);
                $shop->shop_employees()->create([
                    'user_id' => $employee->id,
                ]);
            } */
        }

        $oseaFranchise = Franchise::factory()->create([
            'verified_at' => now(),
            'VAT' => str()->upper('BE' . rand(1000, 9999) . rand(100, 999) . rand(100, 999)),
            'bank_account' => '',
            'country' => 'BE',
        ]);

        $osea = User::whereEmail('oseamatthews@example.xyz')->first();

        $oseaFranchise->franchiseOwner()->create([
            'user_id' => $osea->id,
        ]);

        Franchise::where('name', '')->delete();

        $shps = Shop::all();

        foreach ($shps as $shp) {
            $images = Image::factory()->count(4)->create();

            foreach ($images as $image) {
                $shp->shop_images()->create([
                    'image_id' => $image->id,
                ]);
            }
        }
    }
}
