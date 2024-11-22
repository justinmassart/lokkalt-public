<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.my_favourites')))
    <section class="favourites section">
        <div class="favourites__header">
            <x-main-title icon="heart">@lang('titles.my_favourites')</x-main-title>
        </div>
        @if (auth()->user()->favouriteArticles()->exists())
            <livewire:favourites.favourites-articles-list />
        @endif
        @if (auth()->user()->favouriteShops()->exists())
            <livewire:favourites.favourites-shops-list />
        @endif
        @if (!auth()->user()->favouriteArticles()->exists() && !auth()->user()->favouriteShops()->exists())
            <h3 class="section__title">@lang('titles.no_favourites')</h3>
            <x-button link="{!! route('home') !!}">@lang('buttons.go_back_home')</x-button>
        @endif
    </section>
</x-layouts.app>
