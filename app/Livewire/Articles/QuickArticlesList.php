<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class QuickArticlesList extends Component
{
    use WithoutUrlPagination, WithPagination;

    #[Computed]
    public function articles()
    {
        $country = explode('-', app()->getLocale())[1];

        $articles = Article::where('is_active', true)
            ->whereHas('shops', function ($query) use ($country) {
                $query->where('country', $country);
            })
            ->whereHas('variants', function ($query) {
                $query
                    ->where('is_visible', true)
                    ->whereHas('shopArticle.stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    });
            })
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->orderByDesc(
                Article::selectRaw('avg(score) as avg_score')
                    ->from('article_scores')
                    ->whereColumn('articles.id', 'article_scores.article_id')
            )
            ->take(9)
            ->get();
        $perPage = 3;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $articles->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new LengthAwarePaginator($currentItems, count($articles), $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function render()
    {
        return view('livewire.articles.quick-articles-list');
    }
}
