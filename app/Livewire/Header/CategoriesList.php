<?php

namespace App\Livewire\Header;

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CategoriesList extends Component
{
    #[Computed]
    public function categories()
    {
        $country = explode('-', app()->getLocale())[1];

        return Category::whereHas('articles', function ($query) use ($country) {
            $query->where('is_active', true)
                ->whereHas('shops', function ($query) use ($country) {
                    $query->where('country', $country);
                })
                ->whereHas('variants', function ($query) {
                    $query
                        ->where('is_visible', true)
                        ->whereHas('shopArticle.stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        });
                });
        })
            ->orderBy('name', 'ASC')
            ->pluck('name')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.header.categories-list');
    }
}
