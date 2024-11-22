<?php

namespace App\Livewire\Header;

use App\Models\Article;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class SearchBar extends Component
{
    public string $search = '';

    public bool $show = false;

    public $articlesResults = [];
    public $shopResults = [];

    public function updatedSearch()
    {
        if (strlen($this->search) <= 3) return;

        $country = explode('-', app()->getLocale())[1];

        if (!$this->search) {
            $this->articlesResults = [];
            $this->shopResults = [];
            $this->search = '';
            return;
        }
        $this->articlesResults = Article::search($this->search)
            ->within('articles_index')
            ->query(function (Builder $query) use ($country) {
                $query->where('is_active', true)
                    ->whereHas('shopArticles.variant', function ($query) {
                        $query->where('is_visible', true);
                    })
                    ->whereHas('shopArticles.stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    })
                    ->whereHas('shopArticles.shop', function ($query) use ($country) {
                        $query->where('is_active', true)
                            ->where('country', $country);
                    })
                    ->with([
                        'shopArticles' => function ($query) use ($country) {
                            $query
                                ->whereHas('shop', function ($query) use ($country) {
                                    $query->where('is_active', true)
                                        ->where('country', $country);
                                })
                                ->whereHas('stock', function ($query) {
                                    $query->where('status', '!=', 'out');
                                })
                                ->whereHas('article', function ($query) {
                                    $query->where('is_active', true);
                                })
                                ->whereHas('variant', function ($query) {
                                    $query->where('is_visible', true);
                                })
                                ->with([
                                    'article',
                                    'variant',
                                    'shop',
                                ]);
                        },
                    ]);
            })
            ->take(5)
            ->get();

        $this->shopResults = Shop::search($this->search)
            ->within('shops_index')
            ->query(function (Builder $query) use ($country) {
                $query->where('country', $country)
                    ->whereHas('shopArticles.stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    });
            })
            ->take(5)
            ->get();

        $this->show = true;
    }

    public function emptyForm()
    {
        $this->articlesResults = [];
        $this->shopResults = [];
        $this->search = '';
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.header.search-bar');
    }
}
