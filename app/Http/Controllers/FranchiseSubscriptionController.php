<?php

namespace App\Http\Controllers;

use App\Filament\Dashboard\Resources\PackResource\Pages\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\StripeClient;

class FranchiseSubscriptionController extends Controller
{
    public function confirm(Request $request)
    {
        $clientSecret = $request->input('payment_intent_client_secret');

        if (!$clientSecret) return redirect('/');

        $paymentIntentID = explode('_secret', $clientSecret, 2)[0];

        $stripe = new StripeClient(config('services.stripe.secret'));

        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentID);

        if (!$paymentIntent || $paymentIntent->status !== 'succeeded') {
            session()->flash('payment', $paymentIntent->status);
            return redirect(Subscriptions::getUrl());
        }

        $franchise = session()->get('franchise');

        if (!$franchise) return redirect('/');

        $franchise->refresh();

        try {
            DB::beginTransaction();

            $franchise->subscription()->update([
                'has_paid' => true,
                'stripe_status' => 'active',
            ]);

            foreach ($franchise->packs as $pack) {
                $pack->update([
                    'is_active' => true,
                ]);
            }

            DB::commit();

            session()->flash('subscription', true);

            return redirect('/');
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th);
            return redirect(Subscriptions::getUrl());
        }
    }
}
