<div>
    <section class="after-checkout">
        <div class="after-checkout__header">
            <x-main-title icon="cart">@lang('titles.payment')</x-main-title>
        </div>
        <section class="after-checkout__infos">
            <h2 class="section__title status-message" id="message">@lang('stripe.' . $checkoutStatus)</h2>
            <div class="checkout__stripe__resume card-na">
                <div class="checkout__stripe__resume__table">
                    <h2 class="checkout__stripe__resume__table__title">Resume</h2>
                    <div class="checkout__stripe__resume__table__top">
                        <p class="checkout__stripe__resume__table__top__name">Article Name</p>
                        <p class="checkout__stripe__resume__table__top__quantity">Quantity</p>
                        <p class="checkout__stripe__resume__table__top__unit">Price</p>
                        <p class="checkout__stripe__resume__table__top__total">Total</p>
                    </div>
                    <div class="checkout__stripe__resume__table__list">
                        @foreach (session('cart') as $index => $article)
                            <div class="checkout__stripe__resume__table__list__item">
                                <p class="checkout__stripe__resume__table__list__item__name">{!! $article['name'] !!}
                                    ({!! $article['variant_name'] !!})
                                </p>
                                <p class="checkout__stripe__resume__table__list__item__quantity">{!! $article['quantity'] !!}
                                </p>
                                <p class="checkout__stripe__resume__table__list__item__unit">{!! $article['price'] !!}</p>
                                <p class="checkout__stripe__resume__table__list__item__total">{!! $article['price'] * $article['quantity'] !!}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="checkout__stripe__resume__price">
                    <h2 class="checkout__stripe__resume__price__title">Total of :</h2>
                    <p class="checkout__stripe__resume__price__amount">{!! session()->get('cartSubTotalPrice') !!}€ + @lang('titles.fees') =
                        {!! session()->get('cartPrice') / 100 !!} €
                    </p>
                </div>
            </div>
            @if ($checkoutStatus !== 'requires_payment_method')
                <x-button link="{!! route('home') !!}">@lang('buttons.go_back_home')</x-button>
            @else
                <x-button link="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.cart') !!}">@lang('buttons.try_again')</x-button>
            @endif
        </section>
    </section>
</div>
