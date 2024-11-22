<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPasswordResetToken;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserResetPasswordController extends Controller
{
    public function form()
    {
        return view('reset-password');
    }

    public function notice(string $email)
    {
        if (! $email) {
            return redirect()->back();
        }

        $user = User::whereEmail($email)->first();

        if (! $user || ! $user->passwordResetToken) {
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
        }

        return view('reset-password-notice', compact('email'));
    }

    public function update(string $email, string $token)
    {
        if (! $email || ! $token) {
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
        }

        $user = User::whereEmail($email)->first();
        $resetToken = UserPasswordResetToken::whereToken($token)->first();

        if (! $user || ! $resetToken || $user->passwordResetToken->token !== $resetToken->token) {
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
        }

        return view('reset-password-update', compact('email', 'token'));
    }
}
