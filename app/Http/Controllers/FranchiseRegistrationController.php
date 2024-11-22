<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\FranchiseRegistrationToken;

class FranchiseRegistrationController extends Controller
{
    public function notice(string $email)
    {
        if (! $email) {
            return redirect(route('home'));
        }

        $franchise = Franchise::whereEmail($email)->first();

        if (! $franchise) {
            return redirect(route('home'));
        }

        $isFranchiseInRegistration = $franchise->registrationToken;

        if (! $isFranchiseInRegistration) {
            return redirect(route('home'));
        }

        return view('franchises.franchise-registration-notice', compact('email'));
    }

    public function confirm(string $email, string $token)
    {
        if (! $email || ! $token) {
            return redirect(route('home'));
        }

        $franchise = Franchise::whereEmail($email)->first();

        $registrationToken = FranchiseRegistrationToken::whereToken($token)->whereFranchiseId($franchise->id)->first();

        if (! $franchise || ! $registrationToken) {
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

        $franchise->update([
            'verified_at' => now(),
        ]);

        $franchise->registrationToken->delete();

        return view('franchises.franchise-registration', compact('user'));
    }
}
