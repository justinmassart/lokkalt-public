<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CheckoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $canCheckout = session()->get('canCheckout') === true ? true : false;

        if (! $canCheckout) {
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.cart'));
        }

        session()->forget('canCheckout');

        return view('checkout');
    }
}
