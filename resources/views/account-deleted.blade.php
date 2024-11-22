<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.account_deleted')))
    <x-main-title>@lang('titles.account-deleted')</x-main-title>
    <section class="account-deleted">
        <h3 class="section__title">Votre compte a bien été supprimé.</h3>
        <x-button link="{!! route('home') !!}">@lang('buttons.back_to_home')</x-button>
    </section>
</x-layouts.app>
