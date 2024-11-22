<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.confirm_your_email')))
    <x-main-title>@lang('titles.confirm-your-email')</x-main-title>
    <section class="confirm-email">
        <h3 class="section__title">Un email de confirmation vous a été envoyé.</h3>
        <p class="confirm-email__description">Pour activer votre compte, vous devez d’abord suivre les
            instructions qui vous ont été envoyées par email à l’adresse ci-dessous. N’oubliez pas de regarder vos
            spams.</p>
        <p class="confirm-email__email">{!! $email !!}</p>
        <p class="confirm-email__explanation">Une fois votre compte activer, vous pourrez vous y connecter.</p>
        <x-button link="{!! route('home') !!}">@lang('buttons.back_to_home')</x-button>
    </section>
</x-layouts.app>
