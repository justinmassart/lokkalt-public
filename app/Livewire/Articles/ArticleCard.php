<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use Livewire\Component;

class ArticleCard extends Component
{
    public Article $article;

    public int $key;

    public function render()
    {
        return view('livewire.articles.article-card');
    }
}
