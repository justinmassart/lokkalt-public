<form method="POST" wire:submit='save' class="card-na register__form form" wire:keyup.enter='save'>
    <div class="register__form__top">
        <x-inputs.form-base-input wireModel="firstname" for="profile-firstname" type="text" label="firstname"
            localization="firstname" />
        <x-inputs.form-base-input wireModel="lastname" for="profile-lastname" type="text" label="lastname"
            localization="lastname" />
        <x-inputs.form-base-input wireModel="email" for="profile-email" type="email" label="email"
            localization="email" />
        <x-inputs.form-base-input wireModel="phone" for="profile-phone" type="tel" label="phone_number"
            localization="phone" :required=false :required_display=true />
        @if ($changeEmail)
            <x-inputs.form-base-input emailUpdate="true" wireModel="emailUpdateToken" for="profile-emailUpdate"
                type="text" label="email-update-token" localization="email-update-token" />
        @endif
        @if ($changePhone)
            <x-inputs.form-base-input phoneUpdate="true" wireModel="phoneUpdateToken" for="profile-phoneUpdate"
                type="text" label="phone-update-token" localization="phone-update-token" />
        @endif
        <x-inputs.form-base-input wireModel="address" for="profile-address" type="text" label="address"
            localization="address" :required=false :required_display=true />
        <x-inputs.country wireModel="country" :required=true :required_display=false />
        @if ($changePassword)
            <x-inputs.form-base-input wireModel="oldPassword" for="profile-password" type="password"
                label="old-password" localization="old-password" />
            <x-inputs.form-base-input wireModel="newPassword" for="profile-password" type="password"
                label="new_password" localization="password" />
            <x-inputs.form-base-input wireModel="confirmNewPassword" for="profile-password-confirmation" type="password"
                label="confirm_new_password" localization="new_password_confirmation" />
        @endif
    </div>
    <div class="register__form__bottom">
        <x-button class="register__form__left-btn" livewire="wire:click.prevent='toggleChangePassword'"
            style="outlined">{!! $changePassword ? __('buttons.do_not_change_password') : __('buttons.change_password') !!}</x-button>
        <x-button class="register__form__right-btn" type="submit">@lang('buttons.save')</x-button>
    </div>
</form>
