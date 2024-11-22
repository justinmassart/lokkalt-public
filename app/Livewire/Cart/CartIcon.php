<?php

namespace App\Livewire\Cart;

use App\Livewire\Cart\Cart as CartCart;
use App\Models\ArticleCart;
use App\Models\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class CartIcon extends Component
{
    public int $itemsCount = 0;

    #[On('emptyCart')]
    public function emptyCart()
    {
        session()->forget('stripe_client_secret');
        if (auth()->user()->cart) {
            auth()->user()->cart->delete();
        }
    }

    public function mount()
    {
        if (!auth()->user()) {
            $cart = session()->get('guestCart');
            $this->countGuestItems($cart);

            return;
        }

        if (session()->get('guestCart') !== null) {
            foreach (session()->get('guestCart') as $shopArticleID => $data) {
                $this->addArticleToCart($shopArticleID, $data);
            }
            session()->forget('guestCart');
        }

        $cart = auth()->user()->cart;

        if (!$cart) {
            return;
        }

        $this->countItems($cart->id);
    }

    public function addArticleToCart($shopArticleID, $data)
    {
        $cart = Cart::whereUserId(auth()->user()->id)->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => auth()->user()->id,
            ]);
        }

        $cart->article_carts()->create(
            [
                'shop_article_id' => $shopArticleID,
                'quantity' => $data['quantity'],
            ]
        );

        $this->refreshCount();
    }

    #[On('refreshCount')]
    public function refreshCount()
    {
        if (auth()->user() && auth()->user()->cart) {
            $cart = auth()->user()->cart;
            $this->countItems($cart->id);
        } else {
            $cart = session()->get('guestCart');
            $this->countGuestItems($cart);
        }
    }

    #[On('countGuestItems')]
    public function countGuestItems($cart)
    {
        $count = 0;

        if (!$cart) {
            $this->itemsCount = $count;

            return;
        }

        foreach ($cart as $variantID => $data) {
            $count += $data['quantity'];
        }

        $this->itemsCount = $count;
    }

    #[On('countItems')]
    public function countItems($cartId)
    {
        $this->itemsCount = ArticleCart::whereCartId($cartId)->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart.cart-icon');
    }
}
