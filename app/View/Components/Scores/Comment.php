<?php

namespace App\View\Components\Scores;

use App\Models\Article;
use App\Models\ArticleScore;
use App\Models\Shop;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Comment extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Article $article,
        public ArticleScore $score,
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
        return view('components.scores.comment');
    }
}
