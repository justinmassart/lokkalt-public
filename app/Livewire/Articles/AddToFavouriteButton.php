<?php

namespace App\Livewire\Articles;

use App\Livewire\Favourites\FavouritesArticlesList;
use App\Models\Article;
use App\Models\Variant;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class AddToFavouriteButton extends Component
{
    #[Locked]
    public string $ref;

    #[Locked]
    public string $articleRef;

    // TODO: use isArticleInFavourite like in AddToCartButton

    public function mount(Variant $variant, Article $article)
    {
        $this->articleRef = $article->reference;
        $this->ref = $variant->reference;
    }

    public function redirectUser()
    {
        session()->put('url.intended', url()->previous());
        session()->put('popup', __('popup.not_auth'));
        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
    }

    public function addArticleToFavourite()
    {
        $variant = Variant::whereReference($this->ref)->first();

        if (!$variant) {
            return;
        }

        auth()->user()->user_favourite_articles()->create([
            'article_id' => $variant->article->id,
        ]);
    }

    public function removeArticleFromFavourite()
    {
        $variant = Variant::whereReference($this->ref)->first();

        if (!$variant) {
            return;
        }

        auth()->user()
            ->user_favourite_articles()
            ->where('article_id', $variant->article->id)
            ->first()->delete();

        $this->dispatch('refreshFavouriteArticles')->to(FavouritesArticlesList::class);
    }

    public function render()
    {
        return view('livewire.articles.add-to-favourite-button');
    }
}
