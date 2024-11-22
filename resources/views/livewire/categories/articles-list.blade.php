<section class="results" x-data="{ filter_modal: false }">
    <div class="results__top section__top">
        <h2 class="results__top__title section__title">@lang('titles.list_of_articles') |
            @lang('categories.' . str()->slug($category->name)){!! $subCategory ? ' | ' . __('categories.' . str()->slug($subCategory->name)) : null !!}</h2>
        <div class="results__top__filters">
            <x-button style="outlined" @click.prevent="filter_modal = true">@lang('buttons.filter')</x-button>
            <x-button style="outlined">@lang('buttons.sort')</x-button>
        </div>
    </div>
    <div class="results__list grid">
        @foreach ($this->articles as $index => $article)
            <x-cards.article key="{!! $index !!}" article_name="{!! $article->name !!}" article_price="24,00"
                article_image="{!! $article->images()->where('is_main_image', true)->first()->url ?? $article->images()->first()->url !!}" shop_name="{!! $article->shop->name !!}"
                shop_address="{!! $article->shop->postal_code . ' - ' . $article->shop->city !!}" article_score="{!! $article->scores_avg_rating !!}"
                article_score_count="{!! $article->scores_count !!}" />
        @endforeach
    </div>
    {{ $this->articles->links(data: ['scrollTo' => '.section__top']) }}
    <div class="modal-container" x-cloak x-show="filter_modal" x-transition:enter.duration.400ms
        x-transition:leave.duration.200ms @keydown.escape.window="filter_modal = false">
        <x-modals.category-filters-modal />
    </div>
</section>
