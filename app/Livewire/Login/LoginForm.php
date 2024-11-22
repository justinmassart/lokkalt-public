<?php

namespace App\Livewire\Login;

use App\Mail\RegisterConfirmationMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LoginForm extends Component
{
    #[Validate(['required', 'email'])]
    public string $email;

    #[Validate(['required', 'string'])]
    public string $password;

    public bool $remember = false;

    #[Validate(['boolean'])]
    public bool $needEmailVerification = false;

    public function updatedEmail()
    {
        $this->needEmailVerification = false;
    }

    public function resendVerificationEmail()
    {
        $user = User::whereEmail($this->email)->first();

        if (!$user) {
            $this->addError('email', 'user_not_found');

            return;
        }

        if ($user->emailVerificationToken) {
            $user->emailVerificationToken->delete();
        }

        $token = $user->emailVerificationToken()->create([
            'token' => str()->random(32),
        ]);

        Mail::to($user->email)
            ->queue(new RegisterConfirmationMail($user, $token));

        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirm-email', ['email' => $user->email]));
    }

    public function resetPassword()
    {
        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.reset_password'));
    }

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        $user = User::whereEmail($this->email)->first();

        if (!$user) {
            $this->addError('email', 'user_not_found');

            return;
        }

        if (!$user->email_verified_at) {
            $this->addError('email', 'email_not_verified');
            $this->needEmailVerification = true;

            return;
        }

        $correctPassword = Hash::check($this->password, $user->password);

        if (!$correctPassword) {
            $this->addError('password', 'password_not_match');

            return;
        }

        $creds = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        $cart = session()->get('guestCart');

        if (auth()->attempt($creds, $this->remember)) {
            session()->regenerate();

            if ($cart) {
                session()->put('guestCart', $cart);
            }

            return redirect()->intended();
        }
    }

    public function render()
    {
        return view('livewire.login.login-form');
    }
}
