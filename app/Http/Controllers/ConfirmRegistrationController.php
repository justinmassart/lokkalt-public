<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ConfirmRegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $email, string $token)
    {
        if (! $email || ! $token) {
            return redirect(route('home'));
        }

        $user = User::whereEmail($email)->first();
        $emailToken = EmailVerificationToken::whereToken($token)->first();

        if (! $user || ! $emailToken || $user->emailVerificationToken->token !== $emailToken->token) {
            return redirect(route('home'));
        }

        try {
            DB::beginTransaction();
            $user->email_verified_at = now();
            $user->save();

            $emailToken->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }

        // TODO: create notifications

        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
    }
}
