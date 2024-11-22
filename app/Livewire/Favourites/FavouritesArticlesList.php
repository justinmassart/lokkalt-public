<?php

namespace App\Livewire\Favourites;

use App\Models\Article;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class FavouritesArticlesList extends Component
{
    use WithPagination;

    protected $listeners = ['refreshFavouriteArticles' => '$refresh'];

    #[Computed]
    public function favouriteArticles()
    {
        return Article::whereHas('user_favourite_articles', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })
            ->withAvg('scores', 'score')
            ->withCount('scores')
            ->orderBy('created_at', 'DESC')
            ->paginate(9, pageName: 'articles-page');
    }

    public function render()
    {
        return view('livewire.favourites.favourites-articles-list');
    }
}
