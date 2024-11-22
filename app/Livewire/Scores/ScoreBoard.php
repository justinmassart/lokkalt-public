<?php

namespace App\Livewire\Scores;

use App\Models\Article;
use Livewire\Attributes\On;
use Livewire\Component;

class ScoreBoard extends Component
{
    public Article $article;

    public array $scoreBoard = [];
    public float $scoreAvg = 0;
    public int $scoreCount = 0;


    #[On('refreshScoreBoard')]
    public function mount()
    {
        $tempScores = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        ];

        foreach ($tempScores as $score => $value) {
            $scores = $this->article
                ->scores()
                ->where('score', '>=', $score)
                ->where('score', '<', $score + 1)
                ->count();
            $tempScores[$score] = $scores;
        }

        if (count($tempScores) === 0) return;

        $this->scoreBoard = array_reverse($tempScores, true);
        $this->scoreCount = $this->article->scores()->count() ?? 1;
        $this->scoreAvg = $this->article->scores()->avg('score') ?? 0;
    }

    public function render()
    {
        return view('livewire.scores.score-board');
    }
}
