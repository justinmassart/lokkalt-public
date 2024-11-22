<?php

namespace App\Livewire\Cart;

use App\Models\Cart;
use Livewire\Component;

class CheckoutList extends Component
{
    public Cart $cart;

    public function mount()
    {
        $this->cart = auth()->user()->cart;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $total = 0;

        $articles = session()->get('cart');

        foreach ($articles as $article) {
            $total += ($article['quantity'] * $article['price']);
        }

        $total = $total + ($total * (config('services.stripe.fee_percentage') / 100)) + config('services.stripe.fee_fixed');
        $total = round($total, 2) * 100;

        $sessionTotal = session()->get('cartPrice');

        if ($sessionTotal === $total) {
            return;
        }

        session()->put('cartPrice', $total);
    }

    public function render()
    {
        return view('livewire.cart.checkout-list');
    }
}
