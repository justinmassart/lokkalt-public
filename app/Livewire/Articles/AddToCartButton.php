<?php

namespace App\Livewire\Articles;

use App\Livewire\Cart\CartIcon;
use App\Models\Article;
use App\Models\Cart;
use App\Models\Shop;
use App\Models\ShopArticle;
use App\Models\Variant;
use Livewire\Attributes\Locked;
use Livewire\Component;

class AddToCartButton extends Component
{
    #[Locked]
    public string $ref;

    #[Locked]
    public string $shopSlug;

    #[Locked]
    public bool $isArticleInCart = false;

    #[Locked]
    public array $articlesInCart = [];

    public function mount(Variant $variant, Article $article, Shop $shop)
    {
        $this->ref = $variant->reference;

        $this->shopSlug = $shop->slug;

        $shopArticle = ShopArticle::whereShopId($shop->id)->whereArticleId($article->id)->whereVariantId($variant->id)->first();

        if (! auth()->user()) {
            $this->articlesInCart = session()->get('guestCart') ?? [];
            $this->isArticleInCart = array_key_exists($shopArticle->id, $this->articlesInCart);

            return;
        }

        if (! auth()->user()->cart) {
            return;
        }

        $this->isArticleInCart = auth()->user()->cart->article_carts()->where('shop_article_id', $shopArticle->id)->exists();
        $this->articlesInCart[] = $shopArticle->id;
    }

    public function addArticleToCart()
    {
        $variant = Variant::whereReference($this->ref)->first();
        $article = $variant->article;
        $shop = Shop::whereSlug($this->shopSlug)->first();
        $shopArticle = ShopArticle::whereShopId($shop->id)->whereArticleId($article->id)->whereVariantId($variant->id)->first();

        if (! $article || ! $variant || ! $article->doesHaveVariant($variant) || ! $shop || ! $shopArticle) {
            return;
        }

        if (! auth()->user()) {
            $cart = session()->get('guestCart');

            if (! $cart) {
                $cart = [];
            }

            $cart[$shopArticle->id] = [
                'quantity' => 1,
            ];

            session()->put('guestCart', $cart);

            $this->dispatch('refreshCount')->to(CartIcon::class);

            $this->articlesInCart[] = $shopArticle->id;

            $this->isArticleInCart = true;

            return;
        }

        $cart = Cart::whereUserId(auth()->user()->id)->first();

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => auth()->user()->id,
            ]);
        }

        $cart->article_carts()->create(
            [
                'shop_article_id' => $shopArticle->id,
                'quantity' => 1,
            ]
        );

        $this->dispatch('refreshCount')->to(CartIcon::class);

        $this->articlesInCart[] = $shopArticle->id;

        $this->isArticleInCart = true;
    }

    public function removeArticleFromCart()
    {
        $variant = Variant::whereReference($this->ref)->first();
        $article = $variant->article;
        $shop = Shop::whereSlug($this->shopSlug)->first();
        $shopArticle = ShopArticle::whereShopId($shop->id)->whereArticleId($article->id)->whereVariantId($variant->id)->first();

        if (! $article || ! $variant || ! $article->doesHaveVariant($variant) || ! $shop || ! $shopArticle) {
            return;
        }

        if (! auth()->user()) {
            $cart = session()->get('guestCart');

            unset($cart[$shopArticle->id]);

            session()->put('guestCart', $cart);

            $this->dispatch('refreshCount')->to(CartIcon::class);

            unset($this->articlesInCart[$shopArticle->id]);

            $this->isArticleInCart = false;

            return;
        }

        $cart = Cart::whereUserId(auth()->user()->id)->first();

        $cart->article_carts()->firstWhere('shop_article_id', $shopArticle->id)->delete();

        $this->dispatch('refreshCount')->to(CartIcon::class);

        unset($this->articlesInCart[$shopArticle->id]);

        $this->isArticleInCart = false;

        if (auth()->user()->cart->article_carts()->count() === 0) {
            auth()->user()->cart->delete();
        }
    }

    public function render()
    {
        return view('livewire.articles.add-to-cart-button');
    }
}
