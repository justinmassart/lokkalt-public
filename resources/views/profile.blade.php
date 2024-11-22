<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.my_profile')))
    <section class="profile" x-data="{ showDeleteAction: false, showNotice: false }">
        <div class="section__header">
            <x-main-title icon="profile">@lang('titles.my_profile')</x-main-title>
            <x-button livewire="@click.prevent='showDeleteAction = !showDeleteAction'" color="red" style="outlined"
                x-on:show-notice.window="showNotice = true">@lang('buttons.delete_account')</x-button>
        </div>
        <div class="profile__delete-account card-na" x-cloak x-show="showDeleteAction">
            <p class="profile__delete-account__title">Êtes-vous sûre de vouloir supprimer votre compte ?</p>
            <p class="profile__delete-account__description">Un mail vous sera envoyé afin de vérifier que vous êtes bien
                à l’origine de cette demande. Si vous suivez les instructions contenues dans ce mail, toutes vos données
                seront supprimées et donc irrécupérables.</p>
            <div class="profile__delete-account__actions">
                <x-button @click.prevent="$dispatch('delete-account'); showDeleteAction = false;" color="red"
                    style="outlined">@lang('buttons.delete_my_account')</x-button>
                <x-button livewire="@click.prevent='showDeleteAction = !showDeleteAction'" color="green"
                    style="filled">@lang('buttons.do_not_delete_account')</x-button>
            </div>
        </div>
        <div class="profile__delete-account-notice card-na" x-cloak x-show="showNotice">
            <p class="profile__delete-account__title">Un mail de confirmation vous a été envoyé.</p>
            <p class="profile__delete-account__description">Vous pouvez y suivre les instructions pour supprimer votre
                compte.</p>
        </div>
        <livewire:profile.profile-form />
    </section>
</x-layouts.app>
