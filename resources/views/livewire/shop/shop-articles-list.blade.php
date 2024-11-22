<div>
    @if ($this->articles->isNotEmpty())
        <div class="shop-articles section__header">
            <h2 class="shop-categories__title section__sub-title">@lang('titles.list_of_articles_of') {!! $shop->name !!}</h2>
        </div>
        <div class="shop-articles__list">
            @foreach ($this->articles as $index => $article)
                @php
                    $variant = $article
                        ->variants()
                        ->where('is_visible', true)
                        ->whereHas('shopArticles.stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        })
                        ->whereHas('shops', function ($query) {
                            $query->where('shop_id', $this->shop->id);
                        })
                        ->first();

                    $shop = $variant
                        ->shops()
                        ->where('shop_id', $this->shop->id)
                        ->first();
                @endphp
                <x-cards.article :$article key="{!! $article->reference . $variant->reference !!}" :$variant :$shop />
            @endforeach
        </div>
        {{ $this->articles->links(data: ['scrollTo' => '.shop-articles__list']) }}
    @endif
</div>
