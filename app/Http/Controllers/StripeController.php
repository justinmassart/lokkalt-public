<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;

class StripeController extends Controller
{
    public function pay()
    {
        $secret = session()->get('stripe_client_secret');

        $stripe = new StripeClient(config('services.stripe.secret'));

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

            return response()->json(['clientSecret' => $secret]);
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

        return response()->json(['clientSecret' => $payment->client_secret]);
    }
}
