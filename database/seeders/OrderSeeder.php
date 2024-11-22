<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereRole('user')->get();

        foreach ($users as $user) {
            $randomOrdersCount = rand(1, 3);
            $articles = Article::all();

            for ($i = 0; $i <= $randomOrdersCount; $i++) {
                $randomArticlesCount = rand(10, 15);
                $orderArticles = $articles->random($randomArticlesCount)->groupBy('shop_id');

                foreach ($orderArticles->keys() as $shID) {
                    $order = Order::create([
                        'reference' => str()->random(10),
                        'sub_total' => 0,
                        'total' => 0,
                        'payment_id' => 'pi_3PGNCXCoxzjWcobx0J948OP4',
                        'stripe_secret' => 'pi_3PGNCXCoxzjWcobx0J948OP4_secret_tKDC7IWgnVLrIUrSI08PAdbdV',
                        'shop_id' => $shID,
                        'user_id' => $user->id,
                    ]);

                    foreach ($orderArticles[$shID] as $artcls) {
                        $artcls = is_array($artcls) ? $artcls : [$artcls];
                        foreach ($artcls as $article) {
                            $variant = $article->variants()->first();
                            $price = $variant->prices()->firstWhere('currency', 'EUR')->price;
                            $item = OrderItem::create([
                                'quantity' => rand(1, 2),
                                'price' => $price,
                                'order_id' => $order->id,
                                'article_id' => $article->id,
                                'variant_id' => $variant->id,
                                'shop_id' => $article->shops()->first()->id,
                            ]);

                            $order->update([
                                'sub_total' => $order->sub_total + ($item->price * $item->quantity),
                                'shop_id' => $article->shops()->first()->id,
                            ]);
                        }

                        $subTotal = $order->sub_total;
                        $total = $subTotal + ($subTotal * config('services.stripe.fee_percentage') / 100) + config('services.stripe.fee_fixed');
                        $total = round($total, 2);

                        $order->update([
                            'total' => $total,
                        ]);
                    }
                }
            }
        }
    }
}
