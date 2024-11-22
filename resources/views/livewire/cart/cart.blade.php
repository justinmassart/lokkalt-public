<div class="cart__content">
    @if ($this->articlesInCart && $this->articlesInCart->count() > 0)
        <div class="cart__content__list">
            @foreach ($this->articlesInCart as $index => $articleInCart)
                <div wire:key="{!! time() . $articleInCart->shopArticle->variant . str()->random(5) !!}" class="card-na cart-card">
                    @php
                        $variantMainImage = $articleInCart->shopArticle->variant
                            ->images()
                            ->where('is_main_image', true)
                            ->first();
                        $variantImage = $articleInCart->shopArticle->variant->images()->first();

                        if (!$variantMainImage && !$variantImage) {
                            $img = $articleInCart->shopArticle->article
                                ->images()
                                ->where('is_main_image', true)
                                ->first();
                            $image = $img ? $img->url : $articleInCart->shopArticle->article->images()->first()->url;
                        } else {
                            $variantMainImage ? ($image = $variantMainImage->url) : ($image = $variantImage->url);
                        }

                        $smallUrl = Illuminate\Support\Facades\Cache::get('small_file_url_' . $image);

                        if (!$smallUrl) {
                            $smallUrl = Storage::disk('s3')->temporaryUrl('web/small/' . $image, now()->addHours(10));
                            Illuminate\Support\Facades\Cache::put(
                                'small_file_url_' . $image,
                                $smallUrl,
                                now()->addHours(10),
                            );
                        }
                    @endphp
                    <div class="cart-card__infos-container">
                        <div class="cart-card__image">
                            <img src="{!! $smallUrl !!}" alt="Image de l’article : Val-Dieu Excellence">
                        </div>
                        <div class="cart-card__infos">
                            <h3 class="cart-card__infos__title">{!! $articleInCart->shopArticle->article->name !!}</h3>
                            <p class="cart-card__infos__variant">Variante: {!! $articleInCart->shopArticle->variant->name !!}</p>
                            <p class="cart-card__infos__seller">Vendu par : {!! $articleInCart->shopArticle->shop->name !!}</p>
                            @php
                                $shops = $articleInCart->shopArticle->shop->franchise->shops;
                                $availableShops = [];

                                foreach ($shops as $shop) {
                                    $shopArticles = $shop
                                        ->shopArticles()
                                        ->whereVariantId($articleInCart->shopArticle->variant->id)
                                        ->with([
                                            'stock' => function ($query) {
                                                $query->whereIn('status', ['limited', 'in']);
                                            },
                                            'shop',
                                        ])
                                        ->get();

                                    foreach ($shopArticles as $shopArticle) {
                                        if ($shopArticle->stock === null || $shopArticle->stock->status === 'out') {
                                            continue;
                                        }

                                        if (!in_array($shopArticle->shop, $availableShops)) {
                                            $availableShops[] = $shopArticle->shop;
                                        }
                                    }
                                }
                            @endphp
                            <form method="POST" class="form">
                                <label class="hidden" for="cart-item-shop">@lang('inputs.shop')</label>
                                <span for="cart-item-shop">@lang('inputs.shop'):</span>
                                <select wire:model.change="cartArticles.{!! $articleInCart->shopArticle->id !!}.shop"
                                    name="cart-item-shop" id="cart-item-shop">
                                    <option selected value="{!! $articleInCart->shopArticle->shop->slug !!}">{!! $articleInCart->shopArticle->shop->address !!}</option>
                                    @foreach ($availableShops as $sp)
                                        <option value="{!! $sp->slug !!}">
                                            {!! $sp->address !!}</option>
                                    @endforeach
                                </select>
                            </form>
                            <p class="cart-card__infos__address">{!! $articleInCart->shopArticle->shop->city . ' - ' . $articleInCart->shopArticle->shop->postal_code !!}</p>
                        </div>
                    </div>
                    <div class="cart-card__price">
                        <div class="cart-card__price__header">
                            <p class="cart-card__price__header__stock">{!! ucfirst(__('titles.' . $articleInCart->shopArticle->stock->status)) !!}</p>
                            <p class="cart-card__price__header__delivery">Retrait en magasin</p>
                        </div>
                        @php
                            $currency = session()->get('currency');
                            $unit = $articleInCart->shopArticle->variant->prices()->firstWhere('currency', $currency)
                                ->per;
                            $price = $articleInCart->shopArticle->variant->prices()->firstWhere('currency', $currency)
                                ->price;
                            $quantity = $articleInCart->quantity;

                            $currencySymbols = [
                                'USD' => '$',
                                'EUR' => '€',
                                'GBP' => '£',
                            ];
                            $currencySymbol = $currencySymbols[$currency] ?? $currency;
                        @endphp
                        <div class="cart-card__price__per-unit">
                            <p class="cart-card__price__per-unit__title">@lang('titles.unit_price') :</p>
                            <p class="cart-card__price__per-unit__value">{!! $price !!}
                                {!! $currencySymbol !!}
                            </p>
                        </div>
                        <div class="cart-card__price__sub-title">
                            <p class="cart-card__price__sub-title__title">@lang('titles.sub_total') :</p>
                            <p class="cart-card__price__sub-title__value">{!! $price * $quantity !!}
                                {!! $currencySymbol !!}
                            </p>
                        </div>
                        <div class="cart-card__price__actions">
                            <form method="POST" class="cart-card__price__actions__count form">
                                <label class="hidden" for="cart-item-count">@lang('inputs.quantity')</label>
                                <span for="cart-item-count">@lang('inputs.quantity_txt'):</span>
                                <select wire:model.change='cartArticles.{!! $articleInCart->shopArticle->id !!}.quantity'
                                    name="cart-item-count" id="cart-item-count">
                                    <option value="0">0</option>
                                    @for ($i = 0; $i <= $articleInCart->shopArticle->stock->quantity; $i++)
                                        <option {!! $quantity === $i ? 'selected' : null !!} value="{!! $i !!}">
                                            {!! $i !!}</option>
                                    @endfor
                                </select>
                            </form>
                            <x-button key="{!! time() . $articleInCart->shopArticle->variant->refrence . str()->random(5) !!}" color="red" style="outlined" icon="trash-lid"
                                wire:click="removeArticleFromCart('{!! $articleInCart->shopArticle->id !!}')"></x-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="cart__content__total">
            <div class="card-na cart-total-card">
                <h3 class="cart-total-card__title">@lang('titles.sub_total')</h3>
                <div class="cart-total-card__articles-price">
                    <p class="cart-total-card__articles-price__articles">{!! $articlesCount !!} @lang('titles.articles')
                    </p>
                    <p class="cart-total-card__articles-price__separator">-</p>
                    <p class="cart-total-card__articles-price__price">{!! $subTotal !!}
                        {!! $currencySymbol !!}
                    </p>
                </div>
                @php
                    $feePercentage = config('services.stripe.fee_percentage');
                    $feeFixed = config('services.stripe.fee_fixed');
                @endphp
                <h3 class="cart-total-card__title">@lang('titles.total') <div class="cart-total-card__title__span">+
                        @lang('titles.fees') <x-icons name="info" />
                        <div class="fees">@lang('titles.transaction_fees') = {!! $feePercentage !!}% of total +
                            {!! $feeFixed !!}€</div>
                    </div>
                </h3>
                <div class="cart-total-card__articles-price">
                    <p class="cart-total-card__articles-price__articles">{!! $articlesCount !!} @lang('titles.articles')
                    </p>
                    <p class="cart-total-card__articles-price__separator">-</p>
                    <p class="cart-total-card__articles-price__price">{!! $total !!}
                        {!! $currencySymbol !!}
                    </p>
                </div>
                <x-button wire:click='checkout'>{!! __('buttons.checkout') !!}</x-button>
            </div>
        </div>
    @elseif (isset($this->guestCart))
        <div class="cart__content__list">
            @foreach ($this->guestCart as $index => $shopArticle)
                <div wire:key="{!! $shopArticle->variant->reference . str()->random(5) !!}" class="card-na cart-card">
                    @php
                        $variantMainImage = $shopArticle->variant->images()->where('is_main_image', true)->first();
                        $variantImage = $shopArticle->variant->images()->first();

                        if (!$variantMainImage && !$variantImage) {
                            $img = $shopArticle->variant->article->images()->where('is_main_image', true)->first();
                            $image = $img ? $img->url : $shopArticle->variant->article->images()->first()->url;
                        } else {
                            $variantMainImage ? ($image = $variantMainImage->url) : ($image = $variantImage->url);
                        }

                        $smallUrl = Illuminate\Support\Facades\Cache::get('small_file_url_' . $image);

                        if (!$smallUrl) {
                            $smallUrl = Storage::disk('s3')->temporaryUrl('web/small/' . $image, now()->addHours(10));
                            Illuminate\Support\Facades\Cache::put(
                                'small_file_url_' . $image,
                                $smallUrl,
                                now()->addHours(10),
                            );
                        }
                    @endphp
                    <div class="cart-card__infos-container">
                        <div class="cart-card__image">
                            <img src="{!! $smallUrl !!}" alt="Image de l’article : Val-Dieu Excellence">
                        </div>
                        <div class="cart-card__infos">
                            <h3 class="cart-card__infos__title">{!! $shopArticle->article->name !!}</h3>
                            <p class="cart-card__infos__variant">Variante: {!! $shopArticle->variant->name !!}</p>
                            <p class="cart-card__infos__seller">Vendu par : {!! $shopArticle->shop->name !!}</p>
                            <p class="cart-card__infos__address">{!! $shopArticle->shop->city . ' - ' . $shopArticle->shop->postal_code !!}</p>
                        </div>
                    </div>
                    <div class="cart-card__price">
                        <div class="cart-card__price__header">
                            <p class="cart-card__price__header__stock">{!! __('titles.' . $shopArticle->stock->status) !!}</p>
                            <p class="cart-card__price__header__delivery">Retrait en magasin</p>
                        </div>
                        @php
                            $currency = $shopArticle->variant->prices->first()->currency;
                            $unit = $shopArticle->variant->prices->first()->per;
                            $price = $shopArticle->variant->prices->first()->price;
                            $quantity = $cart[$shopArticle->id]['quantity'];

                            $currencySymbols = [
                                'USD' => '$',
                                'EUR' => '€',
                                'GBP' => '£',
                            ];
                            $currencySymbol = $currencySymbols[$currency] ?? $currency;
                        @endphp
                        <div class="cart-card__price__per-unit">
                            <p class="cart-card__price__per-unit__title">@lang('titles.unit_price') :</p>
                            <p class="cart-card__price__per-unit__value">{!! $price !!}
                                {!! $currencySymbol !!}
                            </p>
                        </div>
                        <div class="cart-card__price__sub-title">
                            <p class="cart-card__price__sub-title__title">@lang('titles.sub_total') :</p>
                            <p class="cart-card__price__sub-title__value">{!! $price * $quantity !!}
                                {!! $currencySymbol !!}
                            </p>
                        </div>
                        <div class="cart-card__price__actions">
                            <form method="POST" class="cart-card__price__actions__count form">
                                <label class="hidden" for="cart-item-count">@lang('inputs.quantity')</label>
                                <span for="cart-item-count">@lang('inputs.quantity_txt'):</span>
                                <select wire:model.change='cartArticles.{!! $shopArticle->id !!}.quantity'
                                    name="cart-item-count" id="cart-item-count">
                                    <option value="0">0</option>
                                    @for ($i = 0; $i <= $shopArticle->stock->quantity; $i++)
                                        <option {!! $quantity === $i ? 'selected' : null !!} value="{!! $i !!}">
                                            {!! $i !!}</option>
                                    @endfor
                                </select>
                            </form>
                            <x-button key="{!! $shopArticle->variant->ref . str()->random(5) !!}" color="red" style="outlined" icon="trash-lid"
                                wire:click="removeArticleFromCart('{!! $shopArticle->id !!}')"></x-button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="cart__content__total">
            <div class="card-na cart-total-card">
                <h3 class="cart-total-card__title">@lang('titles.sub_total')</h3>
                <div class="cart-total-card__articles-price">
                    <p class="cart-total-card__articles-price__articles">{!! $articlesCount !!} @lang('titles.articles')
                    </p>
                    <p class="cart-total-card__articles-price__separator">-</p>
                    <p class="cart-total-card__articles-price__price">{!! $subTotal !!}
                        {!! $currencySymbol !!}
                    </p>
                </div>
                @php
                    $feePercentage = config('services.stripe.fee_percentage');
                    $feeFixed = config('services.stripe.fee_fixed');
                @endphp
                <h3 class="cart-total-card__title">@lang('titles.total') <div class="cart-total-card__title__span">+
                        @lang('titles.fees') <x-icons name="info" />
                        <div class="fees">@lang('titles.transaction_fees') = {!! $feePercentage !!}% of total +
                            {!! $feeFixed !!}€</div>
                    </div>
                </h3>
                <div class="cart-total-card__articles-price">
                    <p class="cart-total-card__articles-price__articles">{!! $articlesCount !!}
                        @lang('titles.articles')
                    </p>
                    <p class="cart-total-card__articles-price__separator">-</p>
                    <p class="cart-total-card__articles-price__price">{!! $total !!}
                        {!! $currencySymbol !!}
                    </p>
                </div>
                <x-button wire:click='checkout'>{!! __('buttons.login-to-checkout') !!}</x-button>
            </div>
        </div>
    @else
        <div class="cart__content__empty">
            <h3 class="section__title">@lang('titles.cart_is_empty')</h3>
            <x-button link="{!! route('home') !!}">@lang('buttons.go_back_home')</x-button>
        </div>
    @endif
</div>
