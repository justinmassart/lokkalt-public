<?php

namespace App\Livewire\Questions;

use App\Models\Article;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class QuestionsList extends Component
{
    use WithoutUrlPagination, WithPagination;

    public Article $article;

    public Shop $shop;

    public bool $addQuestion = false;

    #[Validate(['min:5', 'max:75'])]
    public string $question = '';

    public function mount(Article $article)
    {
        $this->article = $article;
    }

    public function toggleAddQuestion()
    {
        $this->addQuestion = !$this->addQuestion;
    }

    public function sendQuestion()
    {
        $this->validate([
            'question' => ['min:5', 'max:75'],
        ]);

        try {
            DB::beginTransaction();

            $question = auth()->user()->questions()->create([
                'content' => $this->question,
            ]);

            $this->article->article_questions()->create([
                'question_id' => $question->id,
            ]);

            DB::commit();

            $this->addQuestion = false;
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    #[Computed]
    public function questions()
    {
        return $this->article
            ->questions()
            ->whereHas('articleAnswer', function ($query) {
                $query->whereNotNull('answer');
            })
            ->orderBy('updated_at', 'DESC')
            ->paginate(8);
    }

    public function render()
    {
        return view('livewire.questions.questions-list');
    }
}
