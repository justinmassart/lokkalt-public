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
    <div class="shops__top section__top">
        <h2 class="shops__top__title section__title-nm">@lang('titles.shops_list')</h2>
        <div class="section__filters">
            <x-button @click="open = true" class="modal-open-button" style="outlined">@lang('buttons.filters_and_sort')</x-button>
        </div>
    </div>
    <div class="shops__list">
        @foreach ($this->shops as $index => $shop)
            <x-cards.shop-small :key="$index" :shop="$shop" />
        @endforeach
    </div>
    {{ $this->shops->links(data: ['scrollTo' => '.section__top']) }}
    <div class="modal-container" x-cloak x-show="open" x-transition.opacity>
        <div class="modal" @click.away="open = false">
            <div class="modal__top">
                <h3>@lang('titles.filters')</h3>
                <x-icon class="modal-close-button" name="heroicon-o-x-mark" @click="open = false" />
            </div>
            <div class="modal__body">
                <div class="modal__body__top">
                    <p class="modal__body__top__content">@lang('titles.number_of_results') : {!! $this->shopsCount !!}</p>
                    <p wire:click='resetFilters' class="modal__body__top__reset">@lang('titles.reset_filters')</p>
                </div>
                <div class="modal__body__filter modal__body__filter__search">
                    <form class="modal__body__filter__content modal__body__filter__search__form">
                        <label for="searchShops">@lang('inputs.search')</label>
                        <input wire:model.live.debounce.750ms='shopsSearch' type="search" id="searchShops">
                    </form>
                </div>
                <div class="modal__body__filter" x-data="{ open: false }">
                    <p @click="open = !open" class="modal__body__filter__button">@lang('titles.sort_by')</p>
                    <form class="modal__body__filter__content" x-cloak x-show="open"
                        x-transition.scale.origin.top.left>
                        <label for="sortBy">@lang('titles.sortr_by')</label>
                        <select wire:model.live='sortBy' name="sortBy" id="sortBy">
                            <option selected>@lang('inputs.select_sort_logic')</option>
                            <option value="null">@lang('inputs.none')</option>
                            <option value="priceUp">@lang('inputs.price_asc')</option>
                            <option value="priceDown">@lang('inputs.price_desc')</option>
                        </select>
                    </form>
                </div>
                <div class="modal__body__filter" x-data="{ open: false }">
                    <p @click="open = !open" class="modal__body__filter__button">@lang('titles.postal_code')</p>
                    <form class="modal__body__filter__content modal__body__filter__search__form" x-cloak x-show="open"
                        x-transition.scale.origin.top.left>
                        <label for="postalCode">@lang('inputs.postal_code')</label>
                        <input class="" type="number" id="postalCode"
                            wire:model.live.debounce.750ms='postalCode'>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
