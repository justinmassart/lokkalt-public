<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.shops')))
    <x-main-title>@lang('titles.shops')</x-main-title>
    <section class="shops">
        <livewire:shops.shops-list />
    </section>
</x-layouts.app>
