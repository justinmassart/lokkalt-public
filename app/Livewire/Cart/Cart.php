<?php

namespace App\Livewire\Cart;

use App\Livewire\Notifications\Popup;
use App\Models\Shop;
use App\Models\ShopArticle;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Stripe\StripeClient;

class Cart extends Component
{
    public $cart;

    public array $cartArticles = [];

    public int $articlesCount = 0;

    public float $subTotal = 0;

    public float $total = 0;

    public bool $cannotOrder = false;

    public array $articleOutOfStock = [];

    // TODO: optimize the render to the best. Currenlty, 16 models are loaded for only 1 article in cart.

    public function mount()
    {
        if (!auth()->user()) {
            $this->cart = session()->get('guestCart');
            $this->guestCart();

            return;
        }

        if (!auth()->user()->cart) {
            return;
        }

        $this->verifyStock();

        if (count($this->articleOutOfStock) > 0) {
            session()->put('popup', __('titles.cart_articles_out_stock'));
        }

        foreach ($this->articleOutOfStock as $shopArticleID) {
            $shopArticle = ShopArticle::firstWhere('id', $shopArticleID);
            if ($shopArticle->stock->quantity === 0) {
                $this->cart->article_carts()->firstWhere('shop_article_id', $shopArticle->id)->delete();

                continue;
            }
        }

        $this->cart = auth()->user()->cart;
        $this->articlesInCart();
    }

    #[On('cancelStripePayment')]
    public function cancelStripePayment()
    {
        $secret = session()->get('stripe_client_secret');

        if (!$secret) {
            return;
        }

        $parts = explode('_secret', $secret, 2);
        $paymentId = $parts[0];

        $stripe = new StripeClient(config('services.stripe.secret'));
        $stripe->paymentIntents->cancel($paymentId, []);

        session()->forget('stripe_client_secret');
    }

    public function removeArticleFromCart($id)
    {
        if (auth()->user()) {
            $article = $this->cart->article_carts()->firstWhere('shop_article_id', $id);

            if (!$article) {
                return;
            }

            $article->delete();

            unset($this->cartArticles[$id]);

            $this->updateSubTotal();

            $this->dispatch('refreshCount')->to(CartIcon::class);

            if ($this->articlesCount === 0) {
                $this->cancelStripePayment();
            }
        } else {
            $shopArticle = ShopArticle::whereId($id)->first();

            if (!$shopArticle) {
                return;
            }

            $cart = session()->get('guestCart');
            unset($cart[$shopArticle->id]);
            $this->cart = $cart;
            session()->put('guestCart', $cart);
            $this->updateGuestSubTotal();
            $this->dispatch('refreshCount')->to(CartIcon::class);
        }
    }

    public function updatedCartArticles()
    {
        if (auth()->user()) {
            foreach ($this->cartArticles as $id => $data) {
                $cartArticle = $this->cart->article_carts()->firstWhere('shop_article_id', $id);

                if ($cartArticle->shopArticle->shop->slug !== $data['shop']) {
                    $newShop = Shop::firstWhere('slug', $data['shop']);

                    $data['shop'] = $newShop->slug;

                    $articleID = $cartArticle->shopArticle->article_id;
                    $variantID = $cartArticle->shopArticle->variant_id;

                    $newShopArticle = ShopArticle::whereArticleId($articleID)
                        ->whereVariantId($variantID)
                        ->whereHas('shop', function ($query) use ($newShop) {
                            $query->where('id', $newShop->id);
                        })
                        ->first();

                    $this->cartArticles[$newShopArticle->id] = $this->cartArticles[$id];
                    unset($this->cartArticles[$id]);

                    $cartArticle->update([
                        'shop_article_id' => $newShopArticle->id,
                    ]);
                }

                $dbQuantity = $cartArticle->quantity;

                if ($data['quantity'] == 0) {
                    $cartArticle->delete();

                    continue;
                }

                $newShopArticle = ShopArticle::firstWhere('id', $cartArticle->shop_article_id);

                if ($data['quantity'] > $newShopArticle->stock->quantity) {
                    $data['quantity'] = $newShopArticle->stock->quantity;
                }

                $this->cartArticles[$newShopArticle->id]['quantity'] = $data['quantity'];

                if ($dbQuantity == $data['quantity']) {
                    continue;
                }

                $cartArticle->update([
                    'quantity' => $data['quantity'],
                ]);
            }

            $this->updateSubTotal();
        } else {
            foreach ($this->cartArticles as $id => $data) {
                if ($data['quantity'] == 0) {
                    unset($this->cart[$id]);
                    $this->dispatch('refreshCount')->to(CartIcon::class);

                    continue;
                }
                $this->cart[$id]['quantity'] = (int) $data['quantity'];
            }
            session()->put('guestCart', $this->cart);
            $this->updateGuestSubTotal();
        }
    }

    public function updateGuestSubTotal()
    {
        $cart = $this->cart;
        $this->articlesCount = 0;
        $this->subTotal = 0;
        $this->total = 0;

        array_walk($cart, function ($item, $id) {
            $this->articlesCount += $item['quantity'];
            $price = (float) ShopArticle::whereId($id)->first()->variant->prices()->firstWhere('currency', 'EUR')->price;
            $this->subTotal += $price * $item['quantity'];
        });

        $total = $this->subTotal + ($this->subTotal * config('services.stripe.fee_percentage') / 100) + config('services.stripe.fee_fixed');
        $this->total = round($total, 2);
    }

    public function updateSubTotal()
    {
        $cart = $this->cart;

        $this->articlesCount = (int) $cart->article_carts()->sum('quantity');

        $this->subTotal = collect($this->cartArticles)->map(function ($data) {
            return $data['quantity'] * $data['pricePerUnit'];
        })->sum();

        $total = $this->subTotal + ($this->subTotal * config('services.stripe.fee_percentage') / 100) + config('services.stripe.fee_fixed');
        $this->total = round($total, 2);
    }

    #[Computed]
    public function guestCart()
    {
        if (auth()->user()) {
            return null;
        }

        $cart = $this->cart;

        if (!$cart) {
            return;
        }

        $currency = session()->get('currency');

        $shopArticles = ShopArticle::whereIn('id', array_keys($cart))
            ->with([
                'article',
                'variant.prices' => function ($query) use ($currency) {
                    $query->where('currency', $currency);
                },
                'shop',
                'stock',
            ])
            ->get();

        $this->cartArticles = $shopArticles->mapWithKeys(function ($shopArticle) use ($cart) {
            return [
                $shopArticle->id => [
                    'quantity' => $cart[$shopArticle->id]['quantity'],
                    'pricePerUnit' => (float) $shopArticle->variant->prices->first()->price,
                    'shop' => $shopArticle->shop->slug,
                ],
            ];
        })->toArray();

        $this->updateGuestSubTotal();

        return $shopArticles;
    }

    #[Computed]
    public function articlesInCart()
    {
        if (!auth()->user() || !auth()->user()->cart) {
            return null;
        }

        $cart = $this->cart;
        $cart->refresh();
        $currency = session()->get('currency');

        $this->cartArticles = $cart->article_carts->mapWithKeys(function ($item) use ($currency) {
            return [
                $item->shop_article_id => [
                    'quantity' => $item->quantity,
                    'pricePerUnit' => (float) $item->shopArticle->variant->prices()->firstWhere('currency', $currency)->price,
                    'shop' => $item->shopArticle->shop->slug,
                ],
            ];
        })->toArray();

        $this->updateSubTotal();

        return $cart->article_carts;
    }

    protected function verifyStock(): void
    {
        if (!auth()->user()) {
            return;
        }

        $this->cart = auth()->user()->cart;
        $currency = session()->get('currency');

        $this->cartArticles = $this->cart->article_carts->mapWithKeys(function ($item) use ($currency) {
            return [
                $item->shop_article_id => [
                    'quantity' => $item->quantity,
                    'pricePerUnit' => (float) $item->shopArticle->variant->prices()->firstWhere('currency', $currency)->price,
                    'shop' => $item->shopArticle->shop->slug,
                ],
            ];
        })->toArray();

        foreach ($this->cartArticles as $shopArticleID => $data) {
            $shopArticle = $this->cart->article_carts()->firstWhere('shop_article_id', $shopArticleID)->shopArticle;

            $stock = $shopArticle->stock;

            if ($stock->quantity >= $data['quantity']) {
                continue;
            }

            $this->articleOutOfStock[] = $shopArticle->id;
        }
    }

    public function checkout()
    {
        if (!auth()->user()) {
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
        }

        $this->verifyStock();

        if (count($this->articleOutOfStock) > 0) {
            $this->cannotOrder = true;

            return;
        }

        session()->put([
            'canCheckout' => true,
            'cartPrice' => $this->total * 100,
            'cartSubTotalPrice' => $this->subTotal,
        ]);

        $sessionCart = $this->articlesInCart()->map(function ($articleInCart) {
            return [
                'name' => $articleInCart->shopArticle->article->name,
                'variant_name' => $articleInCart->shopArticle->variant->name,
                'price' => $articleInCart->shopArticle->variant->prices()->firstWhere('currency', session()->get('currency'))->price,
                'quantity' => $articleInCart->quantity,
                'article_id' => $articleInCart->shopArticle->article->id,
                'variant_id' => $articleInCart->shopArticle->variant->id,
                'shop_id' => $articleInCart->shopArticle->shop->id,
            ];
        });
        session()->put('cart', $sessionCart);

        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.checkout'));
    }

    public function render()
    {
        return view('livewire.cart.cart');
    }
}
