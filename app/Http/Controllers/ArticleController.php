<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Shop;
use App\Models\ShopArticle;
use App\Models\Variant;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('articles');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop, Article $article, ?Variant $variant = null)
    {
        if (!$shop->isInUserCountry() || !$shop->doesOwnArticle($article) || ($variant && !$article->doesHaveVariant($variant))) {
            return redirect(route('home'));
        }

        $shop->load(['images'])
            ->loadCount(['scores', 'articles' => function ($query) {
                $query->where('is_active', true);
            }])
            ->loadAvg('scores', 'score');

        $article->load([
            'images',
            'variants' => function ($query) {
                $query
                    ->where('is_visible', true)
                    ->whereHas('shopArticle.stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    });
            },
        ])
            ->loadCount('scores')
            ->loadAvg('scores', 'score');

        $shopArticle = null;

        if ($variant) {
            $shopArticle = ShopArticle::whereShopId($shop->id)
                ->whereArticleId($article->id)
                ->whereVariantId($variant->id)
                ->first();
        }

        return view('article', compact('shop', 'article', 'variant', 'shopArticle'));
    }
}
