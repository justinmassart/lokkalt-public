<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesList extends Component
{
    use WithPagination;

    #[Computed]
    public function categories()
    {
        return Category::get();
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function render()
    {
        return view('livewire.categories.categories-list');
    }
}
