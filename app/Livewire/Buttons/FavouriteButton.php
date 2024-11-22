<?php

namespace App\Livewire\Buttons;

use App\Models\Article;
use App\Models\Shop;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class FavouriteButton extends Component
{
    public ?Article $article = null;

    public ?Shop $shop = null;

    public function mount($article = null, $shop = null)
    {
        if ($article) {
            $this->article = $article;
        }
        if ($shop) {
            $this->shop = $shop;
        }
    }

    public function redirectUser()
    {
        if (!auth()->user()) {
            session()->put('url.intended', url()->previous());
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
        }
    }

    public function addArticleToFavourite($article)
    {
        $this->redirectUser();

        $article = Article::find($article);

        if (!$article) {
            return;
        }

        auth()->user()->user_favourite_articles()->create([
            'article_id' => $article->id,
        ]);
    }

    public function addShopToFavourite($shop)
    {
        $this->redirectUser();

        $shop = Shop::find($shop);

        if (!$shop) {
            return;
        }

        auth()->user()->user_favourite_shops()->create([
            'shop_id' => $shop->id,
        ]);
    }

    public function removeArticleFromFavourite($article)
    {
        $this->redirectUser();

        $article = Article::find($article);

        if (!$article) {
            return;
        }

        auth()->user()
            ->user_favourite_articles()
            ->where('article_id', $article->id)
            ->first()->delete();
    }

    public function removeShopFromFavourite($shop)
    {
        $this->redirectUser();

        $shop = Shop::find($shop);

        if (!$shop) {
            return;
        }

        auth()->user()
            ->user_favourite_shops()
            ->where('shop_id', $shop->id)
            ->first()->delete();
    }

    public function render()
    {
        return view('livewire.buttons.favourite-button');
    }
}
