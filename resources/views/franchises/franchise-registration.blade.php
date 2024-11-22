<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.register_franchise')))
    <x-main-title>@lang('titles.account-confirmed')</x-main-title>
    <section class="confirm-email">
        <h3 class="section__title">Votre compte utilisateur et vendeur ont été confirmés !</h3>
        <p class="confirm-email__description">Vous pouvez maintenant vous y connecter avec l’adresse suivante :</p>
        <p class="confirm-email__email">{!! $user->email !!}</p>
        <x-button link="https://dashboard.lokkalt.com">@lang('buttons.log_to_dashboard')</x-button>
        <x-button link="{!! route('login') !!}">@lang('buttons.log_to_account')</x-button>
    </section>
</x-layouts.app>
