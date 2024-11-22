<div x-data="{ open: false }" x-init="$watch('open', value => {
    if (value) {
        this.scrollPosition = window.pageYOffset;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${this.scrollPosition}px`;
        document.body.classList.add('no-scroll');
    } else {
        const scrollBehavior = document.documentElement.style.scrollBehavior;
        document.documentElement.style.scrollBehavior = 'auto';
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.classList.remove('no-scroll');
        window.scrollTo(0, this.scrollPosition);
        document.documentElement.style.scrollBehavior = scrollBehavior;
    }
})">
    @if ($this->articles->isNotEmpty())
        <div class="results__top section__top">
            <h2 class="results__top__title section__title-nm">Liste des articles |
                {!! $category->name !!}{!! $subCategory ? ' | ' . $subCategory->name : null !!}</h2>
            {{-- <div class="results__top__filters">
            <x-button style="outlined" @click.prevent="subCategoryModal = true">Filter</x-button>
            <x-button style="outlined">@lang('buttons.sort')</x-button>
        </div> --}}
            <div class="section__filters">
                <x-button @click="open = true" class="modal-open-button" style="outlined">@lang('buttons.filters_and_sort')</x-button>
            </div>
        </div>
        <div class="results__list grid">
            @if ($this->sortBy === 'priceUp')
                @php
                    $articles = $this->articles->getCollection()->sortBy('variants.0.prices.0.price');
                @endphp
            @elseif ($this->sortBy === 'priceDown')
                @php
                    $articles = $this->articles->getCollection()->sortByDesc('variants.0.prices.0.price');
                @endphp
            @else
                @php
                    $articles = $this->articles->getCollection();
                @endphp
            @endif
            @foreach ($articles as $index => $article)
                @php
                    $variant = $article
                        ->variants()
                        ->where('is_visible', true)
                        ->whereHas('shopArticles.stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        })
                        ->first();

                    $shop = $variant->shops()->first();
                @endphp
                <x-cards.article :$article :key="$index" :$variant :$shop />
            @endforeach

        </div>
        {{ $this->articles->links(data: ['scrollTo' => '.section__top']) }}

        <div class="modal-container" x-cloak x-show="open" x-transition.opacity>
            <div class="modal" @click.away="open = false">
                <div class="modal__top">
                    <h3>@lang('titles.filters')</h3>
                    <x-icon class="modal-close-button" name="heroicon-o-x-mark" @click="open = false" />
                </div>
                <div class="modal__body">
                    <div class="modal__body__top">
                        <p class="modal__body__top__content">@lang('titles.number_of_results') : {!! $this->articlesCount !!}</p>
                        <p wire:click='resetFilters' class="modal__body__top__reset">@lang('titles.reset_filters')</p>
                    </div>
                    <div class="modal__body__filter modal__body__filter__search">
                        <form class="modal__body__filter__content modal__body__filter__search__form">
                            <label for="searchArticles">Search</label>
                            <input wire:model.live.debounce.750ms='articlesSearch' type="search" id="searchArticles">
                        </form>
                    </div>
                    <div class="modal__body__filter" x-data="{ open: false }">
                        <p @click="open = !open" class="modal__body__filter__button">@lang('titles.sort_by')</p>
                        <form class="modal__body__filter__content" x-cloak x-show="open"
                            x-transition.scale.origin.top.left>
                            <label for="sortBy">Sort By</label>
                            <select wire:model.live='sortBy' name="sortBy" id="sortBy">
                                <option selected>Select a sorting logic</option>
                                <option value="null">none</option>
                                <option value="priceUp">Price up</option>
                                <option value="priceDown">Price down</option>
                            </select>
                        </form>
                    </div>
                    <div class="modal__body__filter" x-data="{ open: false }">
                        <p @click="open = !open" class="modal__body__filter__button">@lang('titles.prices')</p>
                        <div class="modal__body__filter__content modal__body__filter__prices" x-cloak x-show="open"
                            x-transition.scale.origin.top.left>
                            <x-inputs.form-base-input wireModel="minPrice" for="minPrice" type="number" label="min"
                                localization="min_price" />
                            <x-inputs.form-base-input wireModel="maxPrice" for="maxPrice" type="number" label="max"
                                localization="max_price" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="empty-message">@lang('titles.no_results_for_articles')</p>
    @endif
</div>
