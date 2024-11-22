<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.reset_password')))
    <section class="auth">
        <x-main-title icon="avatar">@lang('titles.reset_password')</x-main-title>
        <section class="auth__login">
            <h3 class="auth__login__title section__title">@lang('titles.reset_your_password')</h3>
            <livewire:login.reset-password-form />
        </section>
    </section>
</x-layouts.app>
