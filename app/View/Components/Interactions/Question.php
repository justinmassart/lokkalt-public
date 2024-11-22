<?php

namespace App\View\Components\Interactions;

use App\Models\Question as ModelsQuestion;
use App\Models\Shop;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Question extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Shop $shop,
        public ModelsQuestion $question,
    ) {
        $this->question->load(
            'user',
            'articleAnswer.user'
        );
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.interactions.question');
    }
}
