<div class="checkout__stripe__resume__table__list">
    @foreach (session('cart') as $index => $article)
        <div class="checkout__stripe__resume__table__list__item">
            <p class="checkout__stripe__resume__table__list__item__name">{!! $article['name'] !!}
                ({!! $article['variant_name'] !!})
            </p>
            <p class="checkout__stripe__resume__table__list__item__quantity">{!! $article['quantity'] !!}</p>
            <p class="checkout__stripe__resume__table__list__item__unit">{!! $article['price'] !!}</p>
            <p class="checkout__stripe__resume__table__list__item__total">{!! $article['quantity'] * $article['price'] !!}</p>
        </div>
    @endforeach
</div>
