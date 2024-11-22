<section class="favourites__articles section">
    <h3 class="section__title">@lang('titles.favourite-articles')</h3>
    <div class="favourites__articles__list">
        @foreach ($this->favouriteArticles as $index => $article)
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
            <x-cards.article :key="$index" :$article :$variant :$shop />
        @endforeach
    </div>
    {!! $this->favouriteArticles->links(data: ['scrollTo' => '.favourites__articles']) !!}
</section>
