<x-filament-panels::page>
    <div x-data="{}" x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('stripe-url'))]">
        @php
            $shop = session()->get('shop');
            $franchise = session()->get('franchise');
        @endphp

        <x-filament::modal width="2xl" :close-by-escaping="true">
            <x-slot name="trigger" class="mb-4">
                @if ($franchise->subscription)
                    <x-filament::button>
                        @lang('buttons.update_sub')
                    </x-filament::button>
                @else
                    <x-filament::button>
                        @lang('filament.create_sub')
                    </x-filament::button>
                @endif
            </x-slot>

            <x-slot name="heading">
                @lang('filament.modify_your_packs')
            </x-slot>

            @if ($this->canPay)
                <form id="payment-form">
                    <div id="payment-element">
                        <!-- Elements will create form elements here -->
                    </div>
                    <div class="flex">
                        <x-filament::button outlined color="gray" type="button" wire:click='toggleCanPay'>
                            @lang('buttons.cancel')
                        </x-filament::button>
                        <x-filament::button id="submit" type="submit">
                            @lang('buttons.subscribe')
                        </x-filament::button>
                    </div>
                    <div id="error-message">
                        <!-- Display error message to your customers here -->
                    </div>
                </form>
            @else
                <div class="grid grid-cols-2 md:grid-cols-2 gap-4">

                    @foreach ($this->packs as $pack)
                        <div class="grid">

                            <p class="mb-2">{!! $pack->name !!} - {!! $pack->prices->firstWhere('country', $franchise->country)->price !!}€</p>

                            @if ($franchise->packs()->where('pack_id', $pack->id)->where('is_active', true)->exists())
                                <p class="mb-4">@lang('filament.subscribed')</p>
                            @else
                                <p class="mb-4">@lang('filament.not_subscribed')</p>
                            @endif

                            @if (array_key_exists($pack->id, $this->selectedPacks))
                                @if ($pack->name === 'base')
                                    <x-filament::button disabled>
                                        @lang('buttons.required_pack')
                                    </x-filament::button>
                                @else
                                    <x-filament::button wire:click="removeFromPack('{!! $pack->id !!}')">
                                        @lang('buttons.remove_from_pack')
                                    </x-filament::button>
                                @endif
                            @else
                                <x-filament::button outlined wire:click="addToPack('{!! $pack->id !!}')">
                                    @lang('buttons.add_to_pack')
                                </x-filament::button>
                            @endif

                        </div>
                    @endforeach

                </div>

                @if ($franchise->subscription)
                    @if (count($this->selectedPacks) === 0)
                        <x-filament::button disabled>
                            @lang('filament.update_sub')
                        </x-filament::button>
                    @else
                        <x-filament::button wire:click='updateSubscription'>
                            @lang('filament.update_sub')
                        </x-filament::button>
                    @endif
                @else
                    @if (count($this->selectedPacks) === 0)
                        <x-filament::button disabled>
                            @lang('buttons.make_payment')
                        </x-filament::button>
                    @else
                        <x-filament::button wire:click='createSubscription'>
                            @lang('buttons.make_payment')
                        </x-filament::button>
                    @endif
                @endif

            @endif

        </x-filament::modal>
        @if ($franchise->subscription)
            <x-filament::modal width="2xl" :close-by-escaping="true">
                <x-slot name="trigger">
                    <x-filament::button color="danger" outlined>
                        @lang('buttons.delete_sub')
                    </x-filament::button>
                </x-slot>

                <x-slot name="heading">
                    @lang('filament.confirm_sub_deletion')
                </x-slot>

                <div>
                    <p>@lang('filament.sub_deletion_desc')</p>
                </div>
                <div class="flex content-between">
                    <x-filament::button color="primary" @click="isOpen = false">
                        @lang('buttons.cancel')
                    </x-filament::button>
                    <x-filament::button class="ml-auto" color="danger" outlined wire:click='deleteSubscription'>
                        @lang('buttons.confirm_sub_deletion')
                    </x-filament::button>
                </div>
            </x-filament::modal>
        @endif


        <x-filament::section>
            <x-slot name="heading">
                @lang('filament.see_every_packs')
            </x-slot>
            <div class="grid md:grid-cols-2 gap-2">
                @foreach ($this->packs as $pack)
                    <div wire:key="{!! $pack->id !!}">
                        <div class="flex">
                            <p>
                                {!! $pack->name !!} - {!! $pack->prices->firstWhere('country', $franchise->country)->price !!}€
                            </p>
                            @if ($franchise->packs()->where('pack_id', $pack->id)->where('is_active', true)->exists())
                                <p class="px-2" style="color: rgb(55, 92, 71)">@lang('filament.subscribed')</p>
                            @else
                                <p class="px-2" style="color: rgb(190, 0, 0)">@lang('filament.not_subscribed')</p>
                            @endif
                        </div>
                        <ul class="flex-column mt-1">
                            @foreach ($pack->features as $feature)
                                <li class="mt-1"> -&nbsp;{!! __('packs.' . $feature->name) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </x-filament::section>

        @if ($this->canPay)
            @script
                <script type="module">
                    const setupStripe = async function() {
                        const response = await $wire.getStripeParameters();

                        const {
                            stripeKey,
                            clientSecret,
                            appearance,
                            returnUrl
                        } = JSON.parse(response);

                        const stripe = Stripe(stripeKey);

                        const options = {
                            clientSecret: clientSecret,
                        };

                        // Set up Stripe.js and Elements to use in checkout form, passing the client secret obtained in step 5
                        const elements = stripe.elements(options);

                        // Create and mount the Payment Element
                        const paymentElement = elements.create('payment');
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
                                }
                            });

                            if (error) {
                                const messageContainer = document.querySelector('#error-message');
                                messageContainer.textContent = error.message;
                            } else {
                                // Your customer will be redirected to your `return_url`. For some payment
                                // methods like iDEAL, your customer will be redirected to an intermediate
                                // site first to authorize the payment, then redirected to the `return_url`.
                            }
                        });
                    }

                    setupStripe();

                    Livewire.on('refreshStripeForm', async () => {
                        setupStripe();
                    });
                </script>
            @endscript
        @endif
    </div>
</x-filament-panels::page>
