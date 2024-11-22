<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.reset_password')))
    <x-main-title>@lang('titles.reset-password')</x-main-title>
    <section class="reset-password">
        <h3 class="section__title">Un email pour réinitialiser votre mot de passe vous a été envoyé.</h3>
        <p class="reset-password__description">Pour réinitialiser le mot de passe du compte "{!! $email !!}",
            vous devez d’abord suivre les
            instructions qui vous ont été envoyées par email à l’adresse ci-dessous. N’oubliez pas de regarder vos
            spams.</p>
        <p class="reset-password__email">{!! $email !!}</p>
        <x-button link="{!! route('home') !!}">@lang('buttons.back_to_home')</x-button>
    </section>
</x-layouts.app>
