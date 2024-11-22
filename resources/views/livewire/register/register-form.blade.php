<form method="POST" class="card-na register__form" wire:keyup.enter='register'>
    <div class="register__form__top">
        <x-inputs.form-base-input wireModel="firstname" for="register-firstname" type="text" label="firstname"
            localization="firstname" />
        <x-inputs.form-base-input wireModel="lastname" for="register-lastname" type="text" label="lastname"
            localization="lastname" />
        <x-inputs.form-base-input wireModel="email" for="register-email" type="email" label="email"
            localization="email" />
        {{-- TODO: In the country select use css the put the flag before the text --}}
        <x-inputs.country wireModel="country" :required=true :required_display=false />
        <x-inputs.form-base-input wireModel="password" for="register-password" type="password" label="password"
            localization="password" />
        <x-inputs.form-base-input wireModel="confirmPassword" for="register-password-confirmation" type="password"
            label="confirm_password" localization="password_confirmation" />
    </div>
    <div class="register__form__bottom">
        <x-button class="register__form__right-btn" livewire="wire:click.prevent='register'"
            type="submit">@lang('buttons.create_my_account')</x-button>
    </div>
</form>
