<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use App\Models\Shop;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SimilarArticles extends Component
{
    public Article $article;

    public Shop $shop;

    #[Computed]
    public function similarArticles()
    {
        $shop = $this->shop;
        $article = $this->article;

        $country = explode('-', app()->getLocale())[1];

        return Article::where('is_active', true)
            ->whereNot('id', $this->article->id)
            ->whereHas('shops', function ($query) use ($country, $shop) {
                $query->where('country', $country)->where('shop_id', '!=', $shop->id);
            })
            ->whereHas('category', function ($query) use ($article) {
                $query->where('id', $article->category->id);
            })
            ->whereHas('sub_category', function ($query) use ($article) {
                $query->where('id', $article->sub_category->id);
            })
            ->whereHas('variants', function ($query) {
                $query
                    ->where('is_visible', true)
                    ->whereHas('shopArticle.stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    });
            })
            ->inRandomOrder()
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.articles.similar-articles');
    }
}
