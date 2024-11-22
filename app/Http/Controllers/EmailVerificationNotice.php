<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class EmailVerificationNotice extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $email)
    {
        $user = User::whereEmail($email)->first();

        if (! $user || $user->email_verified_at) {
            LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.create-my-account');
        }

        return view('confirm-email', compact('email'));
    }
}
