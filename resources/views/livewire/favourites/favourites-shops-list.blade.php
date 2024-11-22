<section class="favourites__shops section">
    <h3 class="section__title">@lang('titles.favourite-shops')</h3>
    <div class="favourites__shops__list">
        @foreach ($this->favouriteShops as $index => $shop)
            <x-cards.shop-small :key="$index" :$shop />
        @endforeach
    </div>
    {!! $this->favouriteShops->links(data: ['scrollTo' => '.favourites__shops']) !!}
</section>
