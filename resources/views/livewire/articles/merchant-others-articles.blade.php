<div>
    @if ($this->shopArticles->isNotEmpty())
        <h2 class="section__sub-title merchant-other-products__title">@lang('titles.some_other_articles_of')
            {!! $shop->name !!}</h2>
        <div class="merchant-other-products__list">
            @foreach ($this->shopArticles as $index => $a)
                @php
                    $variant = $a
                        ->variants()
                        ->where('is_visible', true)
                        ->whereHas('shopArticles.stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        })
                        ->first();

                    $shop = $variant->shops()->first();
                @endphp
                <x-cards.article :article="$a" key="{!! $a->reference !!}" :$variant :$shop />
            @endforeach
        </div>
    @endif
</div>
