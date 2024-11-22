<?php

namespace App\Livewire\Shops;

use App\Models\Shop;
use Livewire\Attributes\Computed;
use Livewire\Component;

class QuickShopsList extends Component
{
    #[Computed]
    public function shops()
    {
        $country = explode('-', app()->getLocale())[1];

        return Shop::select('shops.*')
            ->selectSub('SELECT MIN(id) FROM shops AS s WHERE s.franchise_id = shops.franchise_id', 'min_id')
            ->where('country', $country)
            ->where('is_active', true)
            ->whereHas('articles', function ($query) {
                $query
                    ->where('is_active', true)
                    ->whereHas('variants', function ($query) {
                        $query
                            ->where('is_visible', true)
                            ->whereHas('shopArticle.stock', function ($query) {
                                $query->where('status', '!=', 'out');
                            });
                    });
            })
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->havingRaw('shops.id = min_id')
            ->orderByDesc(
                Shop::selectRaw('avg(score) as avg_score')
                    ->from('shop_scores')
                    ->whereColumn('shops.id', 'shop_scores.shop_id')
            )
            ->take(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.shops.quick-shops-list');
    }
}
