<?php

namespace App\View\Components\cards;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CategorySmall extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Category $category,
        public $key,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.category-small');
    }
}
