<?php

namespace App\View\Components\Scores;

use App\Models\Article;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Score extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Article $article,
    ) {
        $this->article->with('scores');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.scores.score');
    }
}
