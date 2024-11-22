<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\Shop;
use Illuminate\Http\Request;

class ChangeEstablishmentController extends Controller
{
    public function changeShop(string $shopSlug, Request $request)
    {
        $shop = Shop::whereSlug($shopSlug)->first();

        $canAccessShop = auth()->user()->canAccessShop($shop);

        if (!$shop || !$canAccessShop) {
            return redirect('/');
        }

        session()->put('shop', $shop);
        session()->put('franchise', $shop->franchise);

        $previousUrl = url()->previous();
        $path = parse_url($previousUrl, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        if (count($segments) > 1) {
            array_pop($segments);
        }
        $basePath = implode('/', $segments);
        $baseUrl = url()->to($basePath);
        return redirect($baseUrl);
    }

    public function changeFranchise(string $franchiseID)
    {

        $franchise = Franchise::whereId($franchiseID)->first();

        $canAccessFranchise = auth()->user()->canAccessFranchise($franchise);

        if (!$franchise || !$canAccessFranchise) {
            return redirect('/');
        }

        session()->put('franchise', $franchise);
        session()->forget('shop');

        if ($franchise->shops()->count() === 1) {
            session()->put('shop', $franchise->shops()->first());
        }

        $previousUrl = url()->previous();
        $path = parse_url($previousUrl, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));
        if (count($segments) > 1) {
            array_pop($segments);
        }
        $basePath = implode('/', $segments);
        $baseUrl = url()->to($basePath);
        return redirect($baseUrl);
    }
}
