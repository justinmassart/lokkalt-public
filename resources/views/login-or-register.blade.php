<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.connexion')))
    <section class="auth">
        <x-main-title icon="avatar">@lang('titles.connexion')</x-main-title>
        <section class="auth__login">
            <h3 class="auth__login__title section__title">@lang('titles.already_have_account')</h3>
            <livewire:login.login-form />
        </section>
        <section class="auth__register">
            <h3 class="auth__register__title section__title">@lang('titles.no_account_yet')</h3>
            <div class="card-na auth__register__card">
                <p class="auth__register__card__content">Créer votre compte vous apporte des avantages sur Lokkalt, pour
                    faciliter votre visite ou participer automatiquement aux programmes de fidélité des commerçants !
                </p>
                <x-button link="/creer-mon-compte">@lang('buttons.create_my_account')</x-button>
            </div>
        </section>
    </section>
</x-layouts.app>
