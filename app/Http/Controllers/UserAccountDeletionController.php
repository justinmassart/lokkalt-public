<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAccountDeletionToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserAccountDeletionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $email, string $token, Request $request)
    {
        if (!$email || !$token) {
            return redirect('/');
        }

        $user = User::whereEmail($email)->first();

        if ($user->orders()->whereNotIn('status', ['delivered', 'refunded'])->exists()) {
            session()->flash('popup', __('titles.cannot_delete_account_orders'));
            return redirect('/');
        }

        $deleteToken = UserAccountDeletionToken::whereToken($token)->first();

        if (!$user || !$deleteToken || $user->accountDeletionToken->token !== $deleteToken->token) {
            return abort(403);
        }

        if (auth()->user()) {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();
        }

        $user->delete();

        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.account-deleted'));
    }
}
