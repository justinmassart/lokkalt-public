<?php

namespace App\Livewire\Login;

use App\Models\User;
use App\Models\UserPasswordResetToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdatePasswordForm extends Component
{
    #[Validate(['required', 'min:8'])]
    public string $password;

    #[Validate(['required', 'same:password'])]
    public string $confirmPassword;

    public string $email = '';

    public string $token = '';

    public function mount(string $email, string $token)
    {
        $user = User::whereEmail($email)->first();
        $resetToken = UserPasswordResetToken::whereToken($token)->first();

        if (! $user || ! $resetToken || $user->passwordResetToken->token !== $resetToken->token) {
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
        }

        $this->email = $email;
        $this->token = $token;
    }

    public function updatedPassword()
    {
        $this->validate([
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(2),
            ],
        ]);
    }

    public function resetPassword()
    {
        $this->validate();

        $user = User::whereEmail($this->email)->first();

        $samePasswords = Hash::check($this->password, $user->password);

        if ($samePasswords) {
            $this->addError('password', 'must_be_different_than_old_password');

            return;
        }

        try {
            DB::beginTransaction();

            $user->update([
                'password' => bcrypt($this->password),
            ]);

            UserPasswordResetToken::whereToken($this->token)->first()->delete();

            DB::commit();

            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.login'));
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function render()
    {
        return view('livewire.login.update-password-form');
    }
}
