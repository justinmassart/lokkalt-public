<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use App\Models\Shop;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MerchantOthersArticles extends Component
{
    public Shop $shop;

    public Article $article;

    #[Computed]
    public function shopArticles()
    {
        return Article::where('is_active', true)
            ->where('id', '!=', $this->article->id)
            ->whereHas('shopArticles', function ($query) {
                $query->where('shop_id', $this->shop->id);
            })
            ->whereHas('variants', function ($query) {
                $query
                    ->where('is_visible', true)
                    ->whereHas('shopArticle.stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    });
            })
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.articles.merchant-others-articles');
    }
}
