<?php

namespace App\View\Components\cards;

use App\Models\Variant as ModelsVariant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Variant extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ModelsVariant $variant,
        public string $link = '',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.variant');
    }
}
