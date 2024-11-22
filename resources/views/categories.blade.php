<x-layouts.app>
    @section('pageTitle', ucfirst(__('titles.categories')))
    <x-main-title>@lang('titles.categories')</x-main-title>
    <section class="categories">
        <div class="categories__top section__top">
            <h2 class="categories__top__title section__title">@lang('titles.categories_list')</h2>
        </div>
        <div class="categories__list">
            @foreach ($categories as $index => $category)
                <x-cards.category key={!! $index !!} category_name="{!! $category->name !!}" />
            @endforeach
        </div>
    </section>
</x-layouts.app>
