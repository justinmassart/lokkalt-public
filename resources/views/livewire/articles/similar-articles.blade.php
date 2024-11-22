<div>
    @if ($this->similarArticles->isNotEmpty())
        <h2 class="section__sub-title similar-products__title">@lang('titles.some_similar_articles')</h2>
        <div class="similar-products__list">
            @foreach ($this->similarArticles as $index => $sa)
                @php
                    $variant = $sa
                        ->variants()
                        ->where('is_visible', true)
                        ->whereHas('shopArticles.stock', function ($query) {
                            $query->where('status', '!=', 'out');
                        })
                        ->first();

                    $shop = $variant->shops()->first();
                @endphp
                <x-cards.article :article="$sa" key="{!! $sa->reference . $variant->reference !!}" :$variant :$shop />
            @endforeach
        </div>
    @endif
</div>
