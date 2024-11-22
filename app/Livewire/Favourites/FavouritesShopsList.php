<?php

namespace App\Livewire\Favourites;

use App\Models\Shop;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class FavouritesShopsList extends Component
{
    use WithPagination;

    #[Computed]
    public function favouriteShops()
    {
        return Shop::whereHas('userFavourites', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->paginate(9, pageName: 'shops-page');
    }

    public function render()
    {
        return view('livewire.favourites.favourites-shops-list');
    }
}
