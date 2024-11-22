<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.articles')))
    <x-main-title>@lang('titles.articles')</x-main-title>
    <section class="articles">
        <livewire:articles.articles-list />
    </section>
</x-layouts.app>
