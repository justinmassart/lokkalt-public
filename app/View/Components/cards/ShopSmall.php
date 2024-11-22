<?php

namespace App\View\Components\cards;

use App\Models\Shop;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShopSmall extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Shop $shop,
        public $key,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.shop-small');
    }
}
