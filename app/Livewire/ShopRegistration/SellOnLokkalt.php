<?php

namespace App\Livewire\ShopRegistration;

use App\Livewire\Notifications\Popup;
use App\Mail\FranchiseRegistrationMail;
use App\Models\Franchise;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class SellOnLokkalt extends Component
{
    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $firstname = '';

    #[Validate(['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'])]
    public string $lastname = '';

    #[Validate(['required', 'string', 'email', 'regex:/^(?!.*@lokkalt\.).*$/'])]
    public string $email = '';

    #[Validate(['required', 'in:BE,FR,DE,LU,NL'])]
    public string $country = '';

    #[Validate(['required', 'string'])]
    public string $address = '';

    public array $predictedAddresses = [];

    public string $completedAddress;

    public string $password = '';

    public string $confirmPassword = '';

    #[Validate(['required', 'string', 'min:2', 'max:50'])]
    public string $shopName = '';

    #[Validate(['required', 'email'])]
    public string $shopEmail = '';

    #[Validate(['required', 'in:BE,FR,DE,LU,NL'])]
    public string $shopCountry = '';

    #[Validate(['required', 'integer'])]
    public int $shopPostalCode;

    #[Validate(['required', 'string'])]
    public string $shopCity = '';

    #[Validate(['required', 'string'])]
    public string $shopAddress = '';

    public string $shopPhone = '';

    #[Validate(['string'])]
    public string $bankAccount = '';

    #[Validate(['required', 'string'])]
    public string $vat = '';

    #[Locked]
    public bool $youForm = false;

    #[Locked]
    public bool $shopForm = false;

    public function mount()
    {
        if (!auth()->user()) {
            $this->youForm = true;

            return;
        }

        $this->shopForm = true;

        $this->firstname = auth()->user()->firstname ?? '';
        $this->lastname = auth()->user()->lastname ?? '';
        $this->email = auth()->user()->email ?? '';
        $this->country = auth()->user()->country ?? '';
        $this->address = auth()->user()->address ?? '';
    }

    public function updatedShopPhone()
    {
        $this->validate([
            'shopPhone' => [
                'phone:mobile',
            ],
        ]);
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

    protected function checkBankAccount()
    {
        if (!$this->bankAccount) {
            $this->validate([
                'bankAccount' => 'required',
            ]);
        }

        $this->bankAccount = preg_replace('/[^A-Za-z]/', '', substr($this->bankAccount, 0, 2)) . preg_replace('/[^0-9]/', '', substr($this->bankAccount, 2));

        $ba = $this->bankAccount;
        $uri = "https://openiban.com/validate/$ba?getBIC=true&validateBankCode=true";

        try {
            $client = new Client();
            $response = $client->request('GET', $uri, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
            $data = json_decode($response->getBody(), true);
            $valid = $data['valid'];

            if (!$valid) {
                $this->dispatch('newPopup', type: 'danger', message: __('popup.wrong_ba'))->to(Popup::class);
                //$this->addError('bankAccount', __('titles.incorrect_bank_account'));

                //return false;
            }

            $this->bankAccount = $data['iban'];

            return true;
        } catch (\Throwable $th) {
            $this->dispatch('newPopup', type: 'warning', message: __('popup.cannot_verify_ba'))->to(Popup::class);
        }
    }

    public function updatedBankAccount()
    {
        $this->checkBankAccount();
    }

    public function checkVat(bool $assign = false)
    {
        if (!$this->vat) {
            $this->validate([
                'vat' => 'required',
            ]);
        }

        $this->vat = preg_replace('/[^A-Za-z]/', '', substr($this->vat, 0, 2)) . preg_replace('/[^0-9]/', '', substr($this->vat, 2));
        $vat = $this->vat;

        $uri = "https://controleerbtwnummer.eu/api/validate/$vat.json";

        try {
            $client = new Client();
            $response = $client->request('GET', $uri, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
            $data = json_decode($response->getBody(), true);

            $valid = $data['valid'];

            if (!$valid) {
                $this->dispatch('newPopup', type: 'danger', message: __('popup.wrong_vat'))->to(Popup::class);
                //$this->addError('vat', __('titles.wrong_vat_number'));

                //return false;

                $assign = false;
            }

            if (!$assign) {
                return true;
            }

            $this->shopCountry = $data['countryCode'];
            $this->shopName = $data['name'];
            $this->shopCity = $data['address']['city'];
            $this->shopPostalCode = $data['address']['zip_code'];
            $data['strAddress'] = str_replace("\n", ', ', $data['strAddress']);
            $this->shopAddress = $data['strAddress'] . ' ' . __('countries.' . strtolower(config('locales.supportedCountries.' . $data['countryCode'] . '.name')));
        } catch (\Throwable $th) {
            $this->dispatch('newPopup', type: 'warning', message: __('popup.cannot_verify_vat'))->to(Popup::class);
        }
    }

    public function updatedVat()
    {
        $this->checkVat(true);
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

        try {
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
        } catch (\Throwable $th) {
            //
        }
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

    public function verifyPerson()
    {
        if (!auth()->user()) {
            $this->validate([
                'firstname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
                'lastname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
                'email' => ['required', 'string', 'email', 'unique:users,email', 'regex:/^(?!.*@lokkalt\.).*$/'],
                'country' => ['required', 'in:BE,FR,DE,LU,NL'],
                'address' => ['required', 'string'],
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
                'confirmPassword' => ['same:password'],
            ]);
        } else {
            $this->validate([
                'firstname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
                'lastname' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Za-z \-]+$/'],
                'email' => ['required', 'string', 'email', 'unique:users,email', 'regex:/^(?!.*@lokkalt\.).*$/'],
                'country' => ['required', 'in:BE,FR,DE,LU,NL'],
                'address' => ['required', 'string'],
            ]);
        }

        $this->youForm = false;
        $this->shopForm = true;
    }

    public function verifyShop()
    {
        $this->validate();

        $validBA = $this->bankAccount ? $this->checkBankAccount() : true;
        $validVAT = $this->checkVat();

        if (!$validBA) {
            $this->addError('bankAccount', __('titles.wrong_bank_account'));
            return;
        }

        if (!$validVAT) {
            $this->addError('vat', __('titles.wrong_vat'));
            return;
        }

        try {
            DB::beginTransaction();

            if (!auth()->user()) {
                $userData = [
                    'role' => 'seller',
                    'firstname' => $this->firstname,
                    'lastname' => $this->lastname,
                    'full_name' => $this->firstname . ' ' . $this->lastname,
                    'slug' => str()->slug($this->firstname . ' ' . $this->lastname) . '#' . str()->random(6),
                    'email' => $this->email,
                    'country' => $this->country,
                    'address' => $this->address,
                    'password' => bcrypt($this->password),
                ];

                while (User::whereSlug($userData['slug'])->exists()) {
                    $userData['slug'] = str()->slug("$this->firstname $this->lastname") . '#' . str()->random(6);
                }

                $user = User::create($userData);
            } else {
                $user = auth()->user();
            }

            $franchiseData = [
                'name' => $this->shopName,
                'email' => $this->shopEmail,
                'phone' => $this->shopPhone ?? null,
                'country' => $this->shopCountry,
                'city' => $this->shopCity,
                'postal_code' => $this->shopPostalCode,
                'address' => $this->shopAddress,
                'VAT' => $this->vat,
            ];

            $franchise = Franchise::create($franchiseData);

            $franchise->franchiseOwner()->create([
                'user_id' => $user->id,
            ]);

            $registration = $franchise->registrationToken()->create([
                'token' => str()->random(36),
                'user_id' => $user->id,
            ]);

            DB::commit();

            Mail::to($franchise->email)
                ->queue(new FranchiseRegistrationMail(
                    $user,
                    $franchise,
                    $registration
                ));

            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.franchise-registration-notice', ['email' => $franchise->email]));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function goBack()
    {
        $this->youForm = true;
        $this->shopForm = false;
    }

    public function render()
    {
        return view('livewire.shop-registration.sell-on-lokkalt')->layout('components.layouts.app');
    }
}
