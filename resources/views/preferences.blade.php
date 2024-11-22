<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.my_preferences')))
    <section class="preferences">
        <x-main-title icon="preferences">@lang('titles.my_preferences')</x-main-title>
        <div class="preferences__left card-na">
            <h3 class="preferences__left__title section__title">@lang('titles.notifications_by_email')</h3>
            <form method="POST" class="preferences__left__form form" x-data="{ email_selected: false }">
                <x-inputs.checkbox for="email_preferences" label="@lang('inputs.accept_to_receive_notifications_by_email')"
                    alpine="@click=email_selected=!email_selected" />
                <div class="form__checkboxes-options" :class="{ 'opacity': !email_selected }">
                    <p class="form__checkboxes-options__title">... @lang('titles.when_a_subscribed_seller') ...</p>
                    <x-inputs.checkbox for="email_preferences_product-added" label="@lang('inputs.add_an_article')"
                        disabled=":disabled=!email_selected" behavior=":checked=email_selected" />
                    <x-inputs.checkbox for="email_preferences_lower-price" label="@lang('inputs.lower_prices')"
                        disabled=":disabled=!email_selected" behavior=":checked=email_selected" />
                </div>
                <div class="form__checkboxes-options" :class="{ 'opacity': !email_selected }">
                    <p class="form__checkboxes-options__title">... @lang('titles.when_favourite') ...</p>
                    <x-inputs.checkbox for="email_preferences_product-updated" label="{!! 'est mis à jour' !!}"
                        disabled=":disabled=!email_selected" behavior=":checked=email_selected" />
                    <x-inputs.checkbox for="email_preferences_product-deleted" label="{!! 'est supprimé' !!}"
                        disabled=":disabled=!email_selected" behavior=":checked=email_selected" />
                </div>
                <x-button>Sauvegarder</x-button>
            </form>
        </div>
        <div class="preferences__right card-na">
            <h3 class="preferences__right__title section__title">Notifications par sms</h3>
            <form method="POST" class="preferences__right__form form" x-data="{ sms_selected: false }">
                <x-inputs.checkbox for="sms_preferences" label="{!! 'J’accepte de recevoir des notifications par sms' !!}"
                    alpine="@click=sms_selected=!sms_selected" />
                <div class="form__checkboxes-options" :class="{ 'opacity': !sms_selected }">
                    <x-inputs.checkbox for="sms_preferences_confirm-order" label="{!! 'pour confirmer ma commande en ligne' !!}"
                        disabled=":disabled=!sms_selected" behavior=":checked=sms_selected" />
                    <x-inputs.checkbox for="sms_preferences_delivery" label="{!! 'pour confirmer le retrait en magasin disponible' !!}"
                        disabled=":disabled=!sms_selected" behavior=":checked=sms_selected" />
                    <x-inputs.checkbox for="sms_preferences_problem" label="{!! 'pour m’avertir d’un problème quelconque' !!}"
                        disabled=":disabled=!sms_selected" behavior=":checked=sms_selected" />
                </div>
                <x-button>Sauvegarder</x-button>
            </form>
        </div>
    </section>
</x-layouts.app>
