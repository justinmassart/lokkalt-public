<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $country = explode('-', app()->getLocale())[1];

        $categories = Category::wherehas('articles', function ($query) use ($country) {
            $query->where('is_active', true)
                ->whereHas('shops', function ($query) use ($country) {
                    $query->where('country', $country);
                })
                ->whereHas('variants', function ($query) {
                    $query
                        ->where('is_visible', true)
                        ->whereHas('shopArticle.stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        });
                });
        })
            ->with([
                'sub_categories' => function ($query) use ($country) {
                    $query->whereHas('articles', function ($query) use ($country) {
                        $query->where('is_active', true)
                            ->whereHas('shops', function ($query) use ($country) {
                                $query->where('country', $country);
                            })
                            ->whereHas('variants', function ($query) {
                                $query
                                    ->where('is_visible', true)
                                    ->whereHas('shopArticle.stock', function ($query) {
                                        $query->where('status', '!=', 'out');
                                    });
                            });
                    });
                }
            ])
            ->orderBy('name')
            ->get();

        return view('categories', compact('categories'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, ?SubCategory $subCategory = null)
    {
        if (!$category) {
            return redirect()->back();
        }

        $country = explode('-', app()->getLocale())[1];

        $category->load([
            'sub_categories' => function ($query) use ($country) {
                $query->whereHas('articles', function ($query) use ($country) {
                    $query->where('is_active', true)
                        ->whereHas('shops', function ($query) use ($country) {
                            $query->where('country', $country);
                        })
                        ->whereHas('variants', function ($query) {
                            $query
                                ->where('is_visible', true)
                                ->whereHas('shopArticle.stock', function ($query) {
                                    $query->where('status', '!=', 'out');
                                });
                        });
                });
            }
        ]);

        $selectedSubCategory = $subCategory;

        return view('category', compact('category', 'selectedSubCategory'));
    }
}
