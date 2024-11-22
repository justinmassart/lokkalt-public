<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('shops');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        if (!$shop || !$shop->isInUserCountry()) {
            session()->flash('popup', __('popup.not_in_good_country'));
            return redirect()->back();
        }

        $shop->load(['images'])
            ->loadCount('scores')
            ->loadAvg('scores', 'score');

        /*         $categories = Category::whereHas('articles.shops', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })
            ->distinct()
            ->get(); */

        // TODO: whereHas articles filter on franchiseShops ?

        $franchiseShops = $shop
            ->franchise
            ->shops()
            ->where('id', '!=', $shop->id)
            ->withCount('scores')
            ->withAvg('scores', 'score')
            ->get();

        return view('shop', compact('shop', 'franchiseShops'));
    }
}
