<?php

namespace App\Livewire\Cart;

use App\Models\ShopArticle;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CartAmount extends Component
{
    public ShopArticle $shopArticle;

    public array $articlesInCart = [];

    public bool $isArticleInCart = false;

    public function mount()
    {
        if (!auth()->user()) {
            $this->articlesInCart = session()->get('guestCart') ?? [];
            $this->isArticleInCart = array_key_exists($this->shopArticle->id, $this->articlesInCart);
            return;
        }

        if (!auth()->user()->cart) {
            return;
        }

        $this->isArticleInCart = auth()->user()->cart->article_carts()->where('shop_article_id', $this->shopArticle->id)->exists();
    }

    public function addArticleToCart($shopArticleID)
    {
        $shopArticle = ShopArticle::whereHashedId($shopArticleID)->first();

        if (!$shopArticle) {
            return;
        }

        $shopArticle->addArticleToCart();

        $this->dispatch('refreshCount')->to(CartIcon::class);

        $this->articlesInCart[] = $shopArticle->id;

        $this->isArticleInCart = true;
    }

    public function removeArticleFromCart($shopArticleID)
    {
        $shopArticle = ShopArticle::whereHashedId($shopArticleID)->first();

        if (!$shopArticle) {
            return;
        }

        $shopArticle->removeArticleFromCart();

        $this->dispatch('refreshCount')->to(CartIcon::class);

        unset($this->articlesInCart[$shopArticle->id]);

        $this->isArticleInCart = false;
    }

    public function buyNow($shopArticleID)
    {
        $shopArticle = ShopArticle::whereHashedId($shopArticleID)->first();

        if (!$shopArticle) {
            return;
        }

        $shopArticle->addArticleToCart();

        $this->dispatch('refreshCount')->to(CartIcon::class);

        $this->articlesInCart[] = $shopArticle->id;

        $this->isArticleInCart = true;

        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.cart'));
    }

    public function render()
    {
        return view('livewire.cart.cart-amount');
    }
}
