<div>
    <div class="quick-articles__list grid">
        @foreach ($this->articles as $key => $article)
            @php
                $variant = $article
                    ->variants()
                    ->where('is_visible', true)
                    ->whereHas('shopArticles.stock', function ($query) {
                        $query->whereIn('status', ['limited', 'in']);
                    })
                    ->with([
                        'shopArticles.stock' => function ($query) {
                            $query->whereIn('status', ['limited', 'in']);
                        },
                    ])
                    ->first();

                $shop = $variant->shopArticles->firstWhere('stock', '!=', null)->shop;
            @endphp
            <x-cards.article :$article :$key :$variant :$shop />
        @endforeach
    </div>
    {{ $this->articles->links('pagination::simple-pagination') }}
</div>
