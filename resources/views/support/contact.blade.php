<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.contact')))
    <section class="support">
        <x-main-title>@lang('titles.contact-form')</x-main-title>
        <livewire:support.contact-form />
    </section>
</x-layouts.app>
