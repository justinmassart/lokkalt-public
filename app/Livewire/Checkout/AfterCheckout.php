<?php

namespace App\Livewire\Checkout;

use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShopArticle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Stripe\StripeClient;

class AfterCheckout extends Component
{
    public bool $isCheckoutSuccessful = true;

    public string $checkoutStatus = '';

    protected string $stripeClientSecret = '';

    protected string $paymentId = '';

    public function mount()
    {
        $status = $this->getPaymentStatus();

        if ($status === 'succeeded') {
            $this->checkoutStatus = 'succeeded';
            $this->createOrder();
        } elseif ($status === 'processing') {
            $this->checkoutStatus = 'processing';
        } elseif ($status === 'requires_payment_method') {
            $this->checkoutStatus = 'requires_payment_method';
        } else {
            return;
        }
    }

    public function getPaymentStatus(): string
    {
        $secret = session()->get('stripe_client_secret');

        if (!$secret) {
            return 'not_found';
        }

        $stripePayment = explode('_secret', $secret, 2);
        $paymentId = $stripePayment[0];
        $stripeSecret = $secret;

        $this->paymentId = $paymentId;
        $this->stripeClientSecret = $stripeSecret;

        $stripe = new StripeClient(config('services.stripe.key'));

        $payment = $stripe->paymentIntents->retrieve($paymentId, [
            'client_secret' => $stripeSecret,
        ]);

        return $payment->status;
    }

    public function createOrder()
    {
        $cart = session()->get('cart');
        $cartGrouped = $cart->groupBy('shop_id');

        try {
            DB::beginTransaction();

            foreach ($cartGrouped as $shopId => $cart) {
                $ref = str()->random(10);

                while (Order::whereReference($ref)->exists()) {
                    $ref = str()->random(10);
                }

                $total = 0;
                $subTotal = 0;
                $cart->map(function ($item) use (&$total, &$subTotal) {
                    $total += ((int) $item['quantity']) * ((float) $item['price']);
                    $subTotal += ((int) $item['quantity']) * ((float) $item['price']);
                });
                $total = $subTotal + ($subTotal * config('services.stripe.fee_percentage') / 100) + config('services.stripe.fee_fixed');
                $total = round($total, 2);

                $order = Order::firstOrCreate(
                    [
                        'reference' => $ref,
                        'sub_total' => $subTotal,
                        'total' => $total,
                        'payment_id' => $this->paymentId,
                        'user_id' => auth()->user()->id,
                        'shop_id' => $shopId,
                    ],
                    [
                        'stripe_secret' => $this->stripeClientSecret,
                    ]
                );

                foreach ($cart as $item) {
                    $shopArticle = ShopArticle::whereShopId($item['shop_id'])
                        ->whereArticleId($item['article_id'])
                        ->whereVariantId($item['variant_id'])
                        ->first();

                    OrderItem::firstOrCreate([
                        'quantity' => ((int) $item['quantity']),
                        'price' => ((float) $item['price']),
                        'total' => ((float) $item['price'] * $item['quantity']),
                        'order_id' => $order->id,
                        'shop_article_id' => $shopArticle->id,
                    ]);

                    $oldStockQuantity = $shopArticle->stock->quantity;
                    $newStockQuantity = $oldStockQuantity - $item['quantity'];
                    $newStockStatus = '';

                    if ($newStockQuantity >= 5) {
                        $newStockStatus = 'in';
                    } elseif ($newStockQuantity < 5 && $newStockQuantity > 0) {
                        $newStockStatus = 'limited';
                    } elseif ($newStockQuantity === 0) {
                        $newStockStatus = 'out';
                    }

                    $shopArticle->stock()->update([
                        'quantity' => $newStockQuantity,
                        'status' => $newStockStatus,
                    ]);
                }
            }

            DB::commit();

            session()->forget('stripe_client_secret');

            if (auth()->user()->cart) {
                auth()->user()->cart->delete();
            }

            Mail::to(auth()->user()->email)
                ->queue(new OrderConfirmedMail(auth()->user(), $order));
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th);
        }
    }

    public function render()
    {
        return view('livewire.checkout.after-checkout')->layout('components.layouts.stripe');
    }
}
