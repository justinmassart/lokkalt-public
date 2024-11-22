<form method="POST" class="auth__login__form form card-na" wire:keyup.enter="login">
    <x-inputs.form-base-input needEmailVerification="{!! $needEmailVerification !!}" wireModel="email" for="login-user"
        type="email" label="email" localization="email" />
    <x-inputs.form-base-input canResetPassword="true" wireModel="password" for="login-password" type="password"
        label="password" localization="password" />
    <x-inputs.checkbox wireModel="remember" for="remember" label="{!! __('inputs.remember_me') !!}" />
    <x-button livewire="wire:click.prevent='login'">@lang('buttons.login')</x-button>
</form>
