<?php

namespace App\Livewire\Shops;

use App\Models\Shop;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class ShopsList extends Component
{
    use WithPagination;

    public int $shopsCount = 0;
    #[Validate(['integer', 'string', 'min:1000', 'max:9999'])]
    public $postalCode = '';
    #[Validate(['string'])]
    public string $shopsSearch = '';

    public function resetFilters()
    {
        $this->reset([
            'postalCode',
            'shopsSearch',
        ]);
    }

    #[Computed]
    public function shops()
    {
        $country = explode('-', app()->getLocale())[1];

        $shops = Shop::select('shops.*')
            ->where('country', $country)
            ->where('is_active', true)
            ->selectSub('SELECT MIN(id) FROM shops AS s WHERE s.franchise_id = shops.franchise_id AND s.is_active = 1', 'min_id')
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
            });

        if ($this->shopsSearch) {
            $shops->where(function ($query) {
                $query->where('name', 'like', '%' . $this->shopsSearch . '%')
                    ->orWhere('slug', 'like', '%' . $this->shopsSearch . '%');
            });
        }

        if ($this->postalCode) {
            $shops->where('postal_code', $this->postalCode);
        }

        $shops
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->havingRaw('shops.id = min_id');

        $this->shopsCount = $shops->count();

        return $shops->paginate(12);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function render()
    {
        return view('livewire.shops.shops-list');
    }
}
