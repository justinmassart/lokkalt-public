<?php

namespace App\Livewire\Scores;

use App\Models\Article;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CommentsList extends Component
{
    public Article $article;

    public Shop $shop;

    public int $offset = 0;

    public $scores;

    public bool $isEvaluating = false;

    #[Validate(['min:1', 'max:5'])]
    public float $score = 2.5;

    #[Validate(['min:4', 'max:100', 'string'])]
    public string $comment;

    #[Validate('in:date-desc,date-asc,score-desc,score-asc')]
    public string $sortComments = 'date-desc';

    public function mount(Article $article)
    {
        $this->article = $article;
        $this->offset = 0;
        $this->fetchScores();
    }

    public function toggleEvaluation()
    {
        $this->isEvaluating = !$this->isEvaluating;
    }

    public function updatedSortComments()
    {
        $this->fetchScores();
    }

    public function updatedScore()
    {
        if ($this->score > 5) {
            $this->score = 5;
        }
        if ($this->score < 1) {
            $this->score = 1;
        }

        $this->score = floor($this->score * 100) / 100;
    }

    public function nextScores()
    {
        $this->offset = $this->offset + 3;
        $this->fetchScores();
    }

    public function sendComment()
    {
        $this->score = floor($this->score * 100) / 100;

        $this->validate([
            'score' => ['min:1', 'max:5'],
            'comment' => ['min:4', 'max:100', 'string'],
        ]);

        try {
            DB::beginTransaction();

            auth()->user()->articlesScores()->create([
                'score' => $this->score,
                'comment' => $this->comment ?? null,
                'article_id' => $this->article->id,
            ]);

            DB::commit();

            $this->isEvaluating = false;
            $this->scores = null;
            $this->dispatch('refreshScoreBoard')->to(ScoreBoard::class);
            $this->fetchScores();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    protected function fetchScores()
    {
        $query = $this->article
            ->scores()
            ->with([
                'user',
                'answer.shop',
            ]);

        switch ($this->sortComments) {
            case 'date-desc':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'date-asc':
                $query->orderBy('created_at', 'ASC');
                break;
            case 'score-desc':
                $query->orderBy('score', 'DESC');
                break;
            case 'score-asc':
                $query->orderBy('score', 'ASC');
                break;
        }

        $comments = $query
            ->take(3 + $this->offset)
            ->get();

        $this->scores === null ? $this->scores = collect() : null;
        $this->scores = $comments;
    }

    public function render()
    {
        return view('livewire.scores.comments-list');
    }
}
