<?php

namespace App\Livewire\Login;

use App\Mail\RegisterConfirmationMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ResetPasswordForm extends Component
{
    #[Validate(['required', 'email'])]
    public string $email;

    #[Validate(['boolean'])]
    public bool $needEmailVerification = false;

    public function updatedEmail()
    {
        $this->needEmailVerification = false;
    }

    public function resendVerificationEmail()
    {
        // TODO: wait a certain time before being able to create a confirmation mail again
        $user = User::whereEmail($this->email)->first();

        if (! $user) {
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

    public function sendResetPasswordMail()
    {
        $this->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::whereEmail($this->email)->first();

        if (! $user) {
            $this->addError('email', 'email_not_found');

            return;
        }

        if (! $user->email_verified_at) {
            $this->addError('email', 'email_not_verified');
            $this->needEmailVerification = true;

            return;
        }

        if ($user->passwordResetToken) {
            $user->passwordResetToken->delete();
        }

        $token = $user->passwordResetToken()->create([
            'token' => str()->random(32),
        ]);

        Mail::to($user->email)
            ->queue(new ResetPasswordMail($user, $token));

        return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.reset_password_notice', ['email' => $user->email]));
    }

    public function render()
    {
        return view('livewire.login.reset-password-form');
    }
}
