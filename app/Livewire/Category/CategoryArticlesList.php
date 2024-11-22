<?php

namespace App\Livewire\Category;

use App\Models\Category;
use App\Models\SubCategory;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryArticlesList extends Component
{
    use WithPagination;

    #[Url]
    public $page;

    #[Locked]
    public ?Category $category = null;

    #[Locked]
    public ?SubCategory $subCategory = null;

    #[Validate(['decimal:0,2', 'min:0'])]
    public $minPrice;

    #[Validate(['decimal:0,2', 'min:0'])]
    public $maxPrice;

    public string $sortBy = '';

    #[Validate(['string'])]
    public string $articlesSearch = '';

    #[Locked]
    public $articlesCount = 0;

    public function mount($category, $subCategory)
    {
        $this->category = $category;
        $this->subCategory = $subCategory;
    }

    public function resetFilters()
    {
        $this->reset([
            'minPrice',
            'maxPrice',
            'sortBy',
            'articlesSearch',
        ]);
    }

    public function updatedMinPrice()
    {
        if ($this->maxPrice && $this->minPrice > $this->maxPrice) {
            list($this->minPrice, $this->maxPrice) = [$this->maxPrice, $this->minPrice];
        }
    }

    public function updatedMaxPrice()
    {
        if ($this->minPrice && $this->maxPrice < $this->minPrice) {
            list($this->maxPrice, $this->minPrice) = [$this->minPrice, $this->maxPrice];
        }
    }

    #[Computed]
    public function articles()
    {
        $country = explode('-', app()->getLocale())[1];

        $query = $this->category->articles()
            ->whereHas('shops', function ($query) use ($country) {
                $query->where('country', $country);
            })
            ->whereHas('variants', function ($query) {
                $query
                    ->where('is_visible', true)
                    ->whereHas('shopArticle.stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    });

                if ($this->minPrice) {
                    $query->whereHas('prices', function ($query) {
                        $query->where('currency', 'EUR')->where('price', '>=', (float) $this->minPrice);
                    });
                }

                if ($this->maxPrice) {
                    $query->whereHas('prices', function ($query) {
                        $query->where('currency', 'EUR')->where('price', '<=', (float) $this->maxPrice);
                    });
                }
            })
            ->withCount('scores')
            ->withAvg('scores', 'score');

        if ($this->subCategory !== null) {
            $query = $query->where('sub_category_id', $this->subCategory->id);
        }

        if ($this->articlesSearch) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->articlesSearch . '%')
                    ->orWhere('reference', 'like', '%' . $this->articlesSearch . '%')
                    ->orWhere('slug', 'like', '%' . $this->articlesSearch . '%');
            });
        }

        $this->articlesCount = $query->count();

        return $query->paginate(9);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function render()
    {
        return view('livewire.category.category-articles-list');
    }
}
