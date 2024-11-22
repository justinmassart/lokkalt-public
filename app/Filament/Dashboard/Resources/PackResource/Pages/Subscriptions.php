<?php

namespace App\Filament\Dashboard\Resources\PackResource\Pages;

use App\Filament\Dashboard\Resources\PackResource;
use App\Models\Franchise;
use App\Models\FranchiseSubscription;
use App\Models\Pack;
use App\Models\PackPrice;
use App\Models\Variant;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Stripe\StripeClient;

class Subscriptions extends Page
{
    protected static string $resource = PackResource::class;

    protected static string $model = FranchiseSubscription::class;

    protected static string $view = 'filament.dashboard.resources.pack-resource.pages.subscriptions';

    public $packs = null;

    public $basePack = null;

    public array $selectedPacks = [];

    public $franchisePacks = null;

    public bool $canPay = false;

    public Franchise $franchise;

    public function mount(): void
    {
        $this->packs = Pack::where('is_active', true)
            ->with([
                'prices' => function ($query) {
                    $query->where('currency', 'EUR');
                },
            ])
            ->get();

        $this->franchise = session()->get('franchise');

        $this->basePack = $this->packs->where('name', 'base')->first();
        $this->selectedPacks[$this->basePack->id] = [
            'price' => $this->basePack->prices()->firstWhere('country', $this->franchise->country)->stripe_id,
        ];

        if (session()->has('payment')) {
            Notification::make()
                ->title(__('filament.something_wrong_payment') . session()->get('payment'))
                ->danger()
                ->send();
        }

        if ($this->franchise->subscription) {
            $this->franchisePacks = $this->franchise->packs()->where('is_active', true)->get();

            foreach ($this->franchisePacks as $pk) {
                $this->selectedPacks[$pk->pack_id] = [
                    'price' => $pk->pack->prices()->firstWhere('country', $this->franchise->country)->stripe_id,
                ];
            }
        }
    }

    public function toggleCanPay(): void
    {
        $this->canPay = !$this->canPay;
    }

    public function addToPack($packID): void
    {
        $pack = Pack::whereId($packID)->first();

        $price_stripe_id = $pack->prices()->firstWhere('country', $this->franchise->country)->stripe_id;

        $this->selectedPacks[$pack->id] = [
            'price' => $price_stripe_id,
        ];
    }

    public function removeFromPack($packID): void
    {
        $pack = Pack::whereId($packID)->first();

        if ($pack->name === 'base') return;

        unset($this->selectedPacks[$pack->id]);
    }

    public function createSubscription()
    {
        if (count($this->selectedPacks) === 0) {
            Notification::make()
                ->title(__('filament.at_least_one_pack'))
                ->danger()
                ->send();
            return;
        }

        if (!array_key_exists($this->basePack->id, $this->selectedPacks)) {
            $this->selectedPacks[$this->basePack->id] = [
                'price' => $this->basePack->prices()->firstWhere('country', $this->franchise->country)->stripe_id,
            ];
        }

        $packs = [];

        foreach ($this->selectedPacks as $packID => $data) {
            $packs[] = $data;
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $shop = session()->get('shop');
        $franchise = $this->franchise;

        $customerID = $franchise->stripe_customer_id;

        if (!$customerID) {

            $customer = $stripe->customers->create([
                'email' => $franchise->email,
                'name' => $franchise->name,
                'address' => [
                    'city' => $franchise->city,
                    'country' => $franchise->country,
                    'postal_code' => $franchise->postal_code,
                    'line1' => $franchise->address,
                ],
            ]);

            if (!$customer) {
                Notification::make()
                    ->title(__('filament.general_oups'))
                    ->danger()
                    ->send();
                return;
            }

            $franchise->update([
                'stripe_customer_id' => $customer->id,
            ]);
        }


        $franchiseSubscription = $franchise->load('subscription')->subscription;

        if ($franchiseSubscription) {
            $stripeSubscription = $stripe->subscriptions->retrieve($franchiseSubscription->subscription_id);

            $paymentIntent = $stripe->paymentIntents->retrieve($franchiseSubscription->payment_id);

            $clientSecret = $paymentIntent->client_secret;

            $subscriptionPacks = [];

            $items = $stripeSubscription->items->data;

            foreach ($items as $item) {
                $subscriptionPacks[] = [
                    'price' => $item->price->id,
                ];
            }

            sort($packs);
            sort($subscriptionPacks);

            if ($subscriptionPacks === $packs) {
                session()->put('subscriptionID', $franchiseSubscription->subscription_id);
                session()->put('clientSecret', $clientSecret);
                $this->canPay = true;
                return;
            }

            $stripeSubscription->delete();

            $franchise->subscription()->delete();
        }

        $subscription = $stripe->subscriptions->create([
            'items' => $packs,
            'customer' => $franchise->stripe_customer_id,
            'payment_behavior' => 'default_incomplete',
            'payment_settings' => [
                'save_default_payment_method' => 'on_subscription',
            ],
            'expand' => ['latest_invoice.payment_intent'],
            'proration_behavior' => 'create_prorations',
        ]);

        if (!$subscription) {
            Notification::make()
                ->title(__('filament.general_oups'))
                ->danger()
                ->send();
            return;
        }

        $franchise->subscription()->create([
            'customer_id' => $franchise->stripe_customer_id,
            'subscription_id' => $subscription->id,
            'payment_id' => $subscription->latest_invoice->payment_intent->id,
            'stripe_status' => $subscription->status,
            'stripe_price' => $subscription->latest_invoice->amount_due,
        ]);

        foreach ($this->selectedPacks as $packID => $data) {
            $franchiseHasPack = $franchise->packs()->where('pack_id', $packID)->exists();
            if (!$franchiseHasPack) {
                $franchise->packs()->create([
                    'pack_id' => $packID,
                ]);
            }
        }

        session()->put('subscriptionID', $subscription->id);
        session()->put('clientSecret', $subscription->latest_invoice->payment_intent->client_secret);

        $this->canPay = true;

        $this->dispatch('refreshStripeForm');
    }

    public function getStripeParameters()
    {
        $stripeKey = config('services.stripe.key');

        $clientSecret = session()->get('clientSecret');

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
            'returnUrl' => route('franchise-subscription'),
        ]);
    }

    public function updateSubscription()
    {
        if (count($this->selectedPacks) === 0) {
            Notification::make()
                ->title(__('filament.at_least_one_pack'))
                ->danger()
                ->send();
            return;
        }

        if (!array_key_exists($this->basePack->id, $this->selectedPacks)) {
            $this->selectedPacks[$this->basePack->id] = [
                'price' => $this->basePack->prices()->firstWhere('country', $this->franchise->country)->stripe_id,
            ];
        }

        $stripe = new StripeClient(config('services.stripe.secret'));

        $stripeSubscription = $stripe->subscriptions->retrieve($this->franchise->subscription->subscription_id);

        $items = $stripeSubscription->items->data;

        $selectedPackIds = array_map(function ($pack) {
            return $pack['price'];
        }, $this->selectedPacks);

        $itemIds = array_map(function ($item) {
            return $item->price->id;
        }, $items);

        $itemsToAdd = array_filter($this->selectedPacks, function ($pack) use ($itemIds) {
            return !in_array($pack['price'], $itemIds);
        });

        $itemsToRemove = array_filter($items, function ($item) use ($selectedPackIds) {
            return !in_array($item->price->id, $selectedPackIds);
        });

        foreach ($itemsToAdd as $itemToAdd) {
            $isAdded = $stripe->subscriptionItems->create([
                'subscription' => $stripeSubscription->id,
                'price' => $itemToAdd['price'],
                'quantity' => 1,
            ]);

            if (!$isAdded || $isAdded->quantity !== 1) return;

            $this->franchise->packs()->updateOrCreate([
                'franchise_id' => $this->franchise->id,
                'pack_id' => PackPrice::whereStripeId($itemToAdd['price'])->first()->pack_id,
            ], [
                'is_active' => true,
            ]);
        }

        $itemsToRemoveFromSub = [];

        foreach ($itemsToRemove as $itemToRemove) {
            $itemsToRemoveFromSub[] = [
                'id' => $itemToRemove->id,
                'deleted' => true,
            ];
        }

        if (count($itemsToRemoveFromSub) > 0) {
            $updatedSub = $stripe->subscriptions->update(
                $this->franchise->subscription->subscription_id,
                [
                    'items' => $itemsToRemoveFromSub,
                    'proration_behavior' => 'create_prorations',
                ]
            );

            if (!$updatedSub || $updatedSub->status !== 'active') {
                Notification::make()
                    ->title(__('filament.general_oups'))
                    ->danger()
                    ->send();
                return;
            }
        }

        foreach ($this->franchise->packs as $p) {
            if (!in_array(['price' => $p->pack->prices()->firstWhere('country', $this->franchise->country)->stripe_id], $this->selectedPacks)) {
                $p->update([
                    'is_active' => false,
                ]);
            }
        }

        Notification::make()
            ->title(__('filament.subs_updated'))
            ->success()
            ->send();
    }

    public function deleteSubscription()
    {
        $stripe = new StripeClient(config('services.stripe.secret'));

        $canceledSub = $stripe->subscriptions->cancel($this->franchise->subscription->subscription_id);

        if ($canceledSub->status !== 'canceled') {
            Notification::make()
                ->title(__('filament.general_oups'))
                ->danger()
                ->send();
            return;
        }

        $this->franchise->subscription->delete();
        $this->franchise->packs()->delete();

        foreach ($this->franchise->shops as $shop) {
            $shop->update([
                'is_active' => false,
            ]);

            foreach ($shop->articles as $article) {
                $article->update([
                    'is_active' => false,
                ]);

                Variant::whereArticleId($article->id)->update([
                    'is_visible' => false,
                ]);
            }
        }

        session()->flash('canceled', true);

        $this->franchise->refresh();

        return redirect('/');
    }
}
