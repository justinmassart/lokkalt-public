<?php

namespace App\Livewire\Cart;

use Livewire\Attributes\On;
use Livewire\Component;

class CartSubTotal extends Component
{
    public int $count = 0;

    public function mount($count)
    {
        $this->count = $count;
    }

    #[On('refreshCartSubTotal')]
    public function refreshComponent()
    {
        $this->render();
    }

    public function render()
    {
        return view('livewire.cart.cart-sub-total');
    }
}
