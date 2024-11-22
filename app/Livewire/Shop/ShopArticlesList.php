<?php

namespace App\Livewire\Shop;

use App\Models\Article;
use App\Models\Shop;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ShopArticlesList extends Component
{
    use WithoutUrlPagination, WithPagination;

    public Shop $shop;

    public function mount(Shop $shop)
    {
        $this->shop = $shop;
    }

    #[Computed]
    public function articles()
    {
        return Article::where('is_active', true)
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
            ->paginate(6);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function render()
    {
        return view('livewire.shop.shop-articles-list');
    }
}
