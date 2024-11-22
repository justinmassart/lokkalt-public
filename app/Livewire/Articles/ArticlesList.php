<?php

namespace App\Livewire\Articles;

use App\Models\Article;
use App\Models\Category;
use App\Models\ShopArticle;
use App\Models\SubCategory;
use App\Models\Variant;
use App\Models\VariantPrice;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class ArticlesList extends Component
{
    use WithPagination;

    #[Locked]
    public $categories = [];

    #[Locked]
    public $subCategories = [];

    #[Locked]
    public $selectedCategories = [];

    #[Locked]
    public $selectedSubCategories = [];

    #[Validate(['decimal:0,2', 'min:0'])]
    public $minPrice;

    #[Validate(['decimal:0,2', 'min:0'])]
    public $maxPrice;

    public string $sortBy = '';

    #[Validate(['string'])]
    public string $articlesSearch = '';

    #[Locked]
    public $articlesCount = 0;

    public function mount()
    {
        $country = explode('-', app()->getLocale())[1];

        $this->categories = Category::whereHas('articles', function ($query) use ($country) {
            $query->whereHas('shopArticles', function ($query) use ($country) {
                $query->whereHas('shop', function ($query) use ($country) {
                    $query->where('country', $country);
                });
            });
        })
            ->get();
    }

    public function toggleCategory(string $category)
    {
        $check = in_array($category, $this->selectedCategories);

        if (!$check) {
            $this->selectedCategories[] = $category;
        } else {
            $index = array_search($category, $this->selectedCategories);
            unset($this->selectedCategories[$index]);
        }
    }

    public function toggleSubCategory(string $subCategory)
    {
        $check = in_array($subCategory, $this->selectedSubCategories);

        if (!$check) {
            $this->selectedSubCategories[] = $subCategory;
        } else {
            $index = array_search($subCategory, $this->selectedSubCategories);
            unset($this->selectedSubCategories[$index]);
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'selectedCategories',
            'selectedSubCategories',
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

        $articles = Article::where('is_active', true)
            ->whereHas('shops', function ($query) use ($country) {
                $query->where('country', $country);
            })
            ->whereHas('shopArticles', function ($query) {
                $query
                    ->whereHas('shop', function ($query) {
                        $query->where('is_active', true);
                    })
                    ->whereHas('variant', function ($query) {
                        $query->where('is_visible', true);
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
                    ->whereHas('stock', function ($query) {
                        $query->where('status', '!=', 'out');
                    });
            })
            ->with([
                'shopArticles' => function ($query) {
                    $query->whereHas('variant', function ($query) {
                        $query->where('is_visible', true);
                    })
                        ->whereHas('shop', function ($query) {
                            $query->where('is_active', true);
                        })
                        ->whereHas('stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        })
                        ->with([
                            'variant.prices' => function ($query) {
                                $query->where('currency', 'EUR');
                            },
                            'shop' => function ($query) {
                                $query->where('is_active', true);
                            },
                        ]);
                },
                /*  'variants' => function ($query) {
                    $query
                        ->where('is_visible', true)
                        ->whereHas('shopArticles.stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        });
                },
                'variants.prices' => function ($query) {
                    $query->where('currency', 'EUR');
                }, */
            ])
            ->withCount('scores')
            ->withAvg('scores', 'score');


        if (count($this->selectedCategories) > 0) {
            $categories = $this->selectedCategories;

            $articles->whereHas('category', function ($query) use ($categories) {
                $query->whereIn('slug', $categories);
            });


            $this->subCategories = SubCategory::whereHas('category', function ($query) use ($categories) {
                $query->whereIn('slug', $categories);
            })
                ->whereHas('articles', function ($query) use ($country) {
                    $query->whereHas('shopArticles', function ($query) use ($country) {
                        $query->whereHas('shop', function ($query) use ($country) {
                            $query->where('country', $country);
                        });
                    });
                })
                ->get();
        }

        if (count($this->selectedSubCategories) > 0) {
            $subCategories = $this->selectedSubCategories;

            $articles->whereHas('sub_category', function ($query) use ($subCategories) {
                $query->whereIn('slug', $subCategories);
            });
        }

        if ($this->articlesSearch) {
            $articles->where(function ($query) {
                $query->where('name', 'like', '%' . $this->articlesSearch . '%')
                    ->orWhere('reference', 'like', '%' . $this->articlesSearch . '%')
                    ->orWhere('slug', 'like', '%' . $this->articlesSearch . '%');
            });
        }

        $this->articlesCount = $articles->count();

        return $articles->paginate(12);
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->setPage($page, $pageName);
    }

    public function render()
    {
        return view('livewire.articles.articles-list');
    }
}
