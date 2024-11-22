<div>
    <section class="checkout">
        <div class="checkout__header">
            <x-main-title icon="cart">@lang('titles.checkout')</x-main-title>
        </div>
        <section class="checkout__delivery card-na">
            <h2 class="section__title">@lang('titles.delivery_method')</h2>
            <p class="checkout__delivery__description">Pour l’instant, <strong>uniquement le retrait en magasin est
                    disponible.</strong>
                Vous devrez donc aller chercher les articles de vos commandes directement dans les commerces.</p>
        </section>
        <section class="checkout__stripe stripe">
            <div class="checkout__stripe__resume card-na">
                <div class="checkout__stripe__resume__table">
                    <h2 class="checkout__stripe__resume__table__title">Resume</h2>
                    <div class="checkout__stripe__resume__table__top">
                        <p class="checkout__stripe__resume__table__top__name">Article Name</p>
                        <p class="checkout__stripe__resume__table__top__quantity">Quantity</p>
                        <p class="checkout__stripe__resume__table__top__unit">Price</p>
                        <p class="checkout__stripe__resume__table__top__total">Total</p>
                    </div>
                    <livewire:cart.checkout-list />
                </div>
                <div class="checkout__stripe__resume__price">
                    <h2 class="checkout__stripe__resume__price__title">Total of :</h2>
                    <p class="checkout__stripe__resume__price__amount">{!! session()->get('cartSubTotalPrice') !!}€ + @lang('titles.fees') =
                        {!! session()->get('cartPrice') / 100 !!} €</p>
                </div>
            </div>
            <form id="payment-form">
                <div id="link-authentication-element">
                </div>
                <div id="payment-element">
                </div>
                <button class="button btn-green-filled btn-small" id="submit">@lang('buttons.pay_now')</button>
                <div id="error-message">
                </div>
            </form>
            <div id="messages" role="alert" style="display: none;"></div>
        </section>
    </section>
    @script
        <script>
            const response = await $wire.getStripeParameters();

            const {
                stripeKey,
                clientSecret,
                appearance,
                options,
                returnUrl
            } = JSON.parse(response);

            const stripe = Stripe(stripeKey);

            const elements = stripe.elements({
                clientSecret,
                appearance
            });

            const paymentElement = elements.create('payment', options);

            paymentElement.mount('#payment-element');

            const form = document.getElementById('payment-form');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const {
                    error
                } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: returnUrl,
                    },
                });

                if (error) {
                    const messageContainer = document.querySelector('#error-message');
                    messageContainer.textContent = error.message;
                } else {
                    //
                }
            });
        </script>
    @endscript
    </script>
</div>
