<?php

namespace App\Livewire\Checkout;

use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Stripe\StripeClient;

class Checkout extends Component
{
    protected $canCheckout = false;

    public function canUserCheckout(): bool
    {
        $condition = session()->get('canCheckout') === true ? true : false;

        if (! $condition) {
            return false;
        }

        return true;
    }

    public function createPayment(): string
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $secret = session()->get('stripe_client_secret');

        $price = session()->get('cartPrice');

        if ($secret) {
            $parts = explode('_secret', $secret, 2);
            $paymentId = $parts[0];

            $stripe->paymentIntents->update(
                $paymentId,
                [
                    'amount' => $price,
                    'currency' => 'eur',
                ]
            );

            return $secret;
        }

        $payment = $stripe->paymentIntents->create([
            'amount' => $price,
            'currency' => 'eur',
            'automatic_payment_methods' => ['enabled' => false],
            'payment_method_types' => [
                'bancontact',
                'mobilepay',
                'eps',
                'giropay',
                'ideal',
            ],
        ]);

        session()->put('stripe_client_secret', $payment->client_secret);

        return $payment->client_secret;
    }

    public function getStripeParameters()
    {
        $stripeKey = config('services.stripe.key');

        $clientSecret = session()->get('stripe_client_secret');

        $appearance = [
            'theme' => 'stripe',
            'variables' => [
                'colorPrimary' => '#375C47',
                'colorBackground' => '#FFFFFF',
                'colorText' => '#0D1C15',
                'colorDanger' => '#830000',
                'fontFamily' => 'Josefin Sans, Ideal Sans, system-ui, sans-serif',
                'fontSizeBase' => '20px',
                'spacingUnit' => '5px',
                'borderRadius' => '10px',
            ],
        ];

        $options = [
            'layout' => [
                'type' => 'accordion',
                'defaultCollapsed' => false,
                'radios' => true,
                'spacedAccordionItems' => false,
            ],
        ];

        return json_encode([
            'stripeKey' => $stripeKey,
            'clientSecret' => $clientSecret,
            'appearance' => $appearance,
            'options' => $options,
            'returnUrl' => LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.after-checkout'),
        ]);
    }

    public function mount()
    {
        $this->canCheckout = $this->canUserCheckout();

        if (! $this->canCheckout) {
            return redirect(LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.cart'));
        }

        session()->forget('canCheckout');

        $clientSecret = $this->createPayment();

        if (! $clientSecret) {
            return;
        }

        $this->getStripeParameters();
    }

    public function render()
    {
        return view('livewire.checkout.checkout')->layout('components.layouts.stripe');
    }
}
