<?php

namespace Database\Seeders;

use App\Models\ShopArticle;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shopArticles = ShopArticle::all();

        foreach ($shopArticles as $shopArticle) {
            $quantity = rand(0, 10);
            $status = 'out';

            if ($quantity >= 5) {
                $status = 'in';
            } elseif ($quantity < 5 && $quantity > 0) {
                $status = 'limited';
            } elseif ($quantity === 0) {
                $status = 'out';
            }

            $stock = $shopArticle->stock()->create([
                'quantity' => $quantity,
                'status' => $status,
            ]);

            $shopArticle->stockOperations()->create([
                'stock_before' => 0,
                'stock_after' => $stock->quantity,
                'operation' => '+'.$stock->quantity,
                'comment' => fake()->text(35),
                'stock_id' => $stock->id,
                'user_id' => $shopArticle->shop->owners()->first()->id,
            ]);
        }

        /* $variants = Variant::all();

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
                'shop_id' => $variant->article->shops()->first()->id,
            ]);

            $variant->stockOperations()->create([
                'stock_before' => 0,
                'stock_after' => $stock->quantity,
                'operation' => '+' . $stock->quantity,
                'comment' => fake()->text(35),
                'stock_id' => $stock->id,
                'user_id' => $variant->article->shops()->first()->owners->random()->id,
            ]);
        } */
    }
}
