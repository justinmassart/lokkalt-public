<form method="POST" class="auth__login__form form card-na">
    <x-inputs.form-base-input wireModel="email" needEmailVerification="{!! $needEmailVerification !!}" for="reset-password-user"
        type="email" label="email" localization="email" />
    <div class="auth__login__form__services">
        <x-button livewire="wire:click.prevent='sendResetPasswordMail'">@lang('buttons.reset_password')</x-button>
    </div>
</form>
