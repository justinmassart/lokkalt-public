<?php

namespace App\Livewire\Register;

use App\Mail\RegisterConfirmationMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RegisterForm extends Component
{
    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $firstname = '';

    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $lastname = '';

    #[Validate(['required', 'string', 'email', 'unique:users,email', 'regex:/^(?!.*@lokkalt\.).*$/'])]
    public string $email = '';

    #[Validate(['required', 'in:BE,FR,DE,LU,NL'])]
    public string $country = '';

    public string $password = '';

    #[Validate(['required', 'same:password'])]
    public string $confirmPassword = '';

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

    public function register()
    {
        $this->validate([
            'firstname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
            'lastname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
            'email' => ['required', 'string', 'email', 'unique:users,email', 'regex:/^(?!.*@lokkalt\.).*$/'],
            'country' => ['required', 'in:BE,FR,DE,LU,NL'],
            'password' => [
                'required', 'string', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(2),
            ],
            'confirmPassword' => ['required', 'same:password'],
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'role' => 'user',
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'full_name' => "$this->firstname $this->lastname",
                'slug' => str()->slug("$this->firstname $this->lastname") . '#' . str()->random(6),
                'email' => $this->email,
                'country' => $this->country,
                'password' => bcrypt($this->password),
            ];

            while (User::whereSlug($data['slug'])->exists()) {
                $data['slug'] = str()->slug("$this->firstname $this->lastname") . '#' . str()->random(6);
            }

            $user = User::create($data);

            $token = $user->emailVerificationToken()->create([
                'token' => str()->random(32),
            ]);

            Mail::to($data['email'])
                ->queue(new RegisterConfirmationMail($user, $token));

            DB::commit();

            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirm-email', ['email' => $user->email]));
        } catch (\Throwable $th) {
            DB::rollBack();
            // TODO: notification
        }
    }

    public function render()
    {
        return view('livewire.register.register-form');
    }
}
