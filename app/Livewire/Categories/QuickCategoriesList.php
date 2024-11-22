<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Component;

class QuickCategoriesList extends Component
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
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.categories.quick-categories-list');
    }
}
