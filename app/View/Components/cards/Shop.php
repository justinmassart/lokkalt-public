<?php

namespace App\View\Components\cards;

use App\Models\Shop as ModelsShop;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Shop extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ModelsShop $shop,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.shop');
    }
}
