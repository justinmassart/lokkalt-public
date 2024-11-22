<?php

namespace App\View\Components\cards;

use App\Models\Article;
use App\Models\Shop;
use App\Models\Variant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArticleSmall extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Article $article,
        public Variant $variant,
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
        return view('components.cards.article-small');
    }
}
