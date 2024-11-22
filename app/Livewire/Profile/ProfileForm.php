<?php

namespace App\Livewire\Profile;

use App\Helpers\SMS;
use App\Livewire\Notifications\Popup;
use App\Mail\ConfirmAccountDeletionMail;
use App\Mail\UserEmailUpdateMail;
use App\Models\User;
use App\Models\UserEmailUpdateToken;
use App\Models\UserPhoneUpdateToken;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ProfileForm extends Component
{
    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $firstname = '';

    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $lastname = '';

    #[Validate(['required', 'string', 'email', 'regex:/^(?!.*@lokkalt\.).*$/'])]
    public string $email = '';

    #[Validate(['boolean'])]
    public bool $changeEmail = false;

    #[Validate(['string'])]
    public string $emailUpdateToken = '';

    public string $phone = '';

    public bool $changePhone = false;

    #[Validate(['string'])]
    public string $phoneUpdateToken = '';

    #[Validate(['string'])]
    public string $address = '';

    public array $predictedAddresses = [];

    public string $completedAddress;

    public string $country = '';

    public bool $changePassword = false;

    public string $oldPassword = '';

    public string $newPassword = '';

    #[Validate(['required', 'same:newPassword'])]
    public string $confirmNewPassword = '';

    public function mount()
    {
        $user = auth()->user();

        $this->firstname = $user->firstname ?? '';
        $this->lastname = $user->lastname ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->address = $user->address ?? '';
        $this->country = $user->country ?? '';
    }

    public function toggleChangePassword()
    {
        $this->changePassword = !$this->changePassword;
    }

    public function updatedNewPassword()
    {
        $this->validate([
            'newPassword' => [
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

    public function updatedPhone()
    {
        $this->validate([
            'phone' => [
                'phone:mobile',
                'unique:users,phone,' . auth()->user()->id,
            ],
        ]);
    }

    public function updatedCountry()
    {
        $country = $this->country;
        $this->validate([
            'country' => ['required', 'in:BE,FR,DE,LU,NL'],
            'phone' => ['phone:mobile', "phone:$country", 'unique:users,phone,' . auth()->user()->id],
        ]);
    }

    public function updatedAddress()
    {
        if (!$this->address || strlen($this->address) <= 5) {
            return;
        }

        $this->addressAutoComplete();
    }

    public function addressAutoComplete()
    {
        $apiKey = config('services.google.cloud_api_key');
        $lang = explode('-', app()->getLocale())[0];
        $input = $this->address;
        $types = 'street_address|street_number|postal_code|locality|country';

        $uri = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$input&language=$lang&types=$types&key=$apiKey";
        $client = new Client();
        $response = $client->request('POST', $uri, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        $data = json_decode($response->getBody(), true);
        $this->predictedAddresses = array_map(function ($prediction) {
            return $prediction['description'];
        }, $data['predictions']);
    }

    public function selectAddress(int $index)
    {
        $this->completedAddress = $this->predictedAddresses[$index];
        $this->address = $this->predictedAddresses[$index];
        $this->country = '';
        foreach (config('locales.supportedCountries') as $index => $properties) {
            if (str_contains($this->address, __('countries.' . strtolower($properties['name'])))) {
                $this->country = strtoupper($properties['isoCode']);
            }
        }
        $this->predictedAddresses = [];
    }

    public function closeCompletedAddresses()
    {
        $this->predictedAddresses = [];
    }

    public function save()
    {
        $this->validate([
            'firstname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
            'lastname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
            'email' => ['required', 'string', 'email', 'unique:users,email,' . auth()->user()->id, 'regex:/^(?!.*@lokkalt\.).*$/'],
            'phone' => ['phone:mobile', 'unique:users,phone,' . auth()->user()->id],
            'address' => ['string'],
            'country' => ['required', 'in:BE,FR,DE,LU,NL'],
        ]);

        if ($this->email !== auth()->user()->email && !$this->changeEmail) {
            $usr = User::whereId(auth()->user()->id)->first();

            $updateToken = UserEmailUpdateToken::firstOrCreate(
                [
                    'user_id' => $usr->id,
                ],
                [
                    'token' => str()->random(10),
                ]
            );

            $tokenAge = Carbon::now()->diffInMinutes($updateToken->created_at);
            $sendMail = true;
            if ($tokenAge >= 1 && $tokenAge <= 1440) {
                $sendMail = false;
            }

            if ($sendMail) {
                Mail::to($this->email)->queue(new UserEmailUpdateMail($usr, $updateToken));
            }

            $this->changeEmail = true;
        } elseif ($this->email !== auth()->user()->email && $this->changeEmail && $this->emailUpdateToken) {
            $this->validate([
                'emailUpdateToken' => ['required', 'exists:user_email_update_tokens,token'],
            ]);
            // TODO: making ->first() could be bad because a token may not be unique
            $correctToken = UserEmailUpdateToken::whereToken($this->emailUpdateToken)->first()->user_id === auth()->user()->id;

            if (!$correctToken) {
                $this->addError('emailUpdateToken', 'token_not_match');

                return;
            }
        }

        if ($this->phone && $this->phone !== auth()->user()->phone && !$this->changePhone) {
            $usr = User::whereId(auth()->user()->id)->first();

            $updateToken = UserPhoneUpdateToken::firstOrCreate(
                [
                    'user_id' => $usr->id,
                ],
                [
                    'token' => rand(100000, 999999),
                ]
            );

            $tokenAge = Carbon::now()->diffInMinutes($updateToken->created_at);
            $sendSMS = true;
            if ($tokenAge >= 1 && $tokenAge <= 1440) {
                $sendSMS = false;
            }

            if ($sendSMS) {
                SMS::send($this->phone, 'Lokkalt phone update token : ' . $updateToken->token);
            }

            $this->changePhone = true;
        } elseif ($this->phone !== auth()->user()->phone && $this->changePhone && $this->phoneUpdateToken) {
            $this->validate([
                'phoneUpdateToken' => ['required', 'exists:user_phone_update_tokens,token'],
            ]);
            // TODO: making ->first() could be bad because a token may not be unique
            $correctToken = UserPhoneUpdateToken::whereToken($this->phoneUpdateToken)->first()->user_id === auth()->user()->id;

            if (!$correctToken) {
                $this->addError('phoneUpdateToken', 'token_not_match');

                return;
            }
        }

        if ($this->changePassword) {
            $this->validate([
                'oldPassword' => ['required', 'string'],
                'newPassword' => [
                    'required',
                    'string',
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                        ->uncompromised(2),
                ],
                'confirmNewPassword' => [
                    'required',
                    'same:newPassword',
                ],
            ]);

            $checkOldPassword = Hash::check($this->oldPassword, auth()->user()->password);
            $checkPasswords = Hash::check($this->newPassword, auth()->user()->password);

            if (!$checkOldPassword) {
                $this->addError('oldPassword', 'old_password_not_match');
            }

            if ($checkPasswords) {
                $this->addError('newPassword', 'new_password_same_as_old_password');
            }

            if (!$checkOldPassword || $checkPasswords) {
                return;
            }
        }

        if (($this->changeEmail && !$this->emailUpdateToken) || ($this->changePhone && !$this->phoneUpdateToken)) {
            return;
        }

        try {
            DB::beginTransaction();

            $user = User::whereId(auth()->user()->id)->first();

            // TODO: it should’t update the slug every time

            $data = [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'full_name' => "$this->firstname $this->lastname",
                'country' => $this->country,
                'address' => $this->address,
            ];

            if ($this->firstname !== $user->firstname || $this->lastname !== $user->lastname) {
                $data['slug'] = str()->slug("$this->firstname $this->lastname") . '#' . str()->random(6);
                while (User::whereSlug($data['slug'])->exists()) {
                    $data['slug'] = str()->slug("$this->firstname $this->lastname") . '#' . str()->random(6);
                }
            }

            if ($this->changePassword && $this->oldPassword && $this->newPassword && $this->confirmNewPassword) {
                $data['password'] = bcrypt($this->newPassword);
            }

            if ($this->changeEmail && $this->email) {
                $data['email'] = $this->email;
            }

            if ($this->changePhone && $this->phone) {
                $data['phone'] = $this->phone ?? null;
            }

            $user->update($data);

            DB::commit();

            if ($this->changeEmail && $this->email) {
                if ($user->emailUpdateToken) {
                    $user->emailUpdateToken()->delete();
                }
            }

            if ($this->changePhone && $this->phone) {
                if ($user->phoneUpdateToken) {
                    $user->phoneUpdateToken()->delete();
                }
            }

            $this->redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-profile'));
        } catch (\Throwable $th) {
            DB::rollBack();
            // TODO: remove dd and replace it by notification
            dd($th);
        }
    }

    #[On('delete-account')]
    public function sendDeleteAccountMail()
    {
        $check = auth()->user()->orders()->whereNotIn('status', ['delivered', 'refunded'])->exists();

        if ($check) {
            $this->dispatch('newPopup', type: 'warning', message: __('titles.cannot_delete_account_orders'))->to(Popup::class);
            return;
        }

        $user = User::whereId(auth()->user()->id)->first();

        if ($user->accountDeletionToken) {
            $user->accountDeletionToken()->delete();
        }

        $deleteToken = $user->accountDeletionToken()->create([
            'token' => str()->random(32),
        ]);

        Mail::to($user->email)
            ->queue(new ConfirmAccountDeletionMail($user, $deleteToken));

        $this->dispatch('show-notice');
    }

    public function render()
    {
        return view('livewire.profile.profile-form');
    }
}
