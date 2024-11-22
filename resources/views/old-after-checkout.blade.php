<x-layouts.stripe>
    @section('pageTitle', ucfirst(__('titles.payment')))
    <section class="after-checkout">
        <div class="after-checkout__header">
            <x-main-title icon="cart">@lang('titles.payment')</x-main-title>
        </div>
        <section class="after-checkout__infos">
            <h2 class="section__title" id="message"></h2>
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
            {{-- TODO: create a second view for failed checkout --}}
            @if (session()->get('payment') === 'success')
                <x-button link="{!! route('home') !!}">@lang('buttons.go_back_home')</x-button>
            @else
                <x-button link="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.cart') !!}">@lang('buttons.try_again')</x-button>
            @endif
        </section>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const stripe = Stripe('{!! config('services.stripe.key') !!}');

            const clientSecret = new URLSearchParams(window.location.search).get(
                'payment_intent_client_secret'
            );

            const translations = {
                'succeeded': '@lang('stripe.succeeded')',
                'processing': '@lang('stripe.processing')',
                'requires_payment_method': '@lang('stripe.requires_payment_method')',
                'default': '@lang('stripe.default')',
            };

            stripe.retrievePaymentIntent(clientSecret).then(({
                paymentIntent
            }) => {
                const message = document.querySelector('#message')

                switch (paymentIntent.status) {
                    case 'succeeded':
                        message.innerText = translations['succeeded'];
                        Livewire.dispatch('order-succeeded');
                        break;

                    case 'processing':
                        message.innerText = translations['processing'];
                        Livewire.dispatch('order-processing');
                        break;

                    case 'requires_payment_method':
                        message.innerText = translations['requires_payment_method'];
                        Livewire.dispatch('order-again');
                        break;

                    default:
                        message.innerText = translations['default'];
                        break;
                }
            });
        });
    </script>
</x-layouts.stripe>
