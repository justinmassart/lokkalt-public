<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopRegistrationToken;

class ShopRegistrationController extends Controller
{
    public function notice(string $email)
    {
        if (! $email) {
            return redirect(route('home'));
        }

        $shop = Shop::whereEmail($email)->first();

        if (! $shop) {
            return redirect(route('home'));
        }

        $isShopInRegistration = $shop->registrationTokens;

        if (! $isShopInRegistration) {
            return redirect(route('home'));
        }

        return view('shops.shop-registration-notice', compact('email'));
    }

    public function confirm(string $email, string $token)
    {
        if (! $email || ! $token) {
            return redirect(route('home'));
        }

        $shop = Shop::whereEmail($email)->first();

        $registrationToken = ShopRegistrationToken::whereToken($token)->whereShopId($shop->id)->first();

        if (! $shop || ! $registrationToken) {
            return redirect(route('home'));
        }

        $user = $registrationToken->user;

        if (! $user) {
            return redirect(route('home'));
        }

        $user->update([
            'role' => 'seller',
            'email_verified_at' => now(),
        ]);

        $shop->update([
            'verified_at' => now(),
        ]);

        $shop->registrationTokens->delete();

        return view('shops.shop-registration', compact('user'));
    }
}
