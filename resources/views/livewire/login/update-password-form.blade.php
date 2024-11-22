<form method="POST" class="auth__login__form form card-na">
    <x-inputs.form-base-input wireModel="password" for="reset-password" type="password" label="password"
        localization="password" />
    <x-inputs.form-base-input wireModel="confirmPassword" for="reset-password-confirmation" type="password"
        label="confirm_password" localization="password_confirmation" />
    <div class="auth__login__form__services">
        <x-button livewire="wire:click.prevent='resetPassword'">@lang('buttons.update_password')</x-button>
    </div>
</form>
