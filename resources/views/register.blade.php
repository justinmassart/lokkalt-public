<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.creation_of_account')))
    <section class="register">
        <x-main-title icon="add-account">@lang('titles.creation_of_account')</x-main-title>
        <livewire:register.register-form />
    </section>
</x-layouts.app>
