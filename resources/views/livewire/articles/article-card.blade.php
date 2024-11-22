<div wire:key="{!! $key !!}" class="card article-card article-card-4C" itemscope
    itemtype="https://schema.org/Product">
    <a class="card-link" title="{!! __('links.article') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.article', ['shop' => $article->shop->slug, 'article' => $article->slug, 'variant' => $article->variants()->first()->slug]) }}"
        itemprop="url">{!! __('links.article', ['article' => $article->name]) !!}</a>
    <div class="card__image article-card__image">
        @php
            $variantMainImage = $article->variants()->first()->images()->where('is_main_image', true)->first();
            $variantImage = $article->variants()->first()->images()->first();

            if (!$variantMainImage && !$variantImage) {
                $img = $article->images->where('is_main_image', true)->first();
                $image = $img ? $img->url : $article->images->first()->url;
            } else {
                $variantMainImage ? ($image = $variantMainImage->url) : ($image = $variantImage->url);
            }

            $smallUrl = Illuminate\Support\Facades\Cache::get('small_file_url_' . $image);
            $mediumUrl = Illuminate\Support\Facades\Cache::get('medium_file_url_' . $image);
            $bigUrl = Illuminate\Support\Facades\Cache::get('big_file_url_' . $image);

            if (!$smallUrl) {
                $smallUrl = Storage::disk('s3')->temporaryUrl('web/small/' . $image, now()->addHours(10));
                Illuminate\Support\Facades\Cache::put('small_file_url_' . $image, $smallUrl, now()->addHours(10));
            }

            if (!$mediumUrl) {
                $mediumUrl = Storage::disk('s3')->temporaryUrl('web/medium/' . $image, now()->addHours(10));
                Illuminate\Support\Facades\Cache::put('medium_file_url_' . $image, $mediumUrl, now()->addHours(10));
            }

            if (!$bigUrl) {
                $bigUrl = Storage::disk('s3')->temporaryUrl('web/big/' . $image, now()->addHours(10));
                Illuminate\Support\Facades\Cache::put('big_file_url_' . $image, $bigUrl, now()->addHours(10));
            }
        @endphp
        <img width="440" height="270" src="{!! $mediumUrl !!}"
            srcset="
        {!! $smallUrl !!} 200w,
        {!! $mediumUrl !!} 400w,
        {!! $bigUrl !!} 800w"
            sizes="(max-width: 400px) 200px, (max-width: 800px) 400px, 800px" alt="{!! __('image.description', ['subject' => $article->name]) !!}"
            itemprop="image">
        <div class="card__image__price-favourite article-card__image__price-favourite">
            <div class="card__image__price-favourite__price article-card__image__price-favourite__price">
                @php
                    $priceModel =
                        $article->variants()->first()->prices->where('currency', session('currency'))->first() ??
                        $article->variants()->first()->prices->first();
                    $price = $priceModel->price;
                    $currency = $priceModel->currency;
                    $unit = $priceModel->per;

                    $currencySymbols = [
                        'USD' => '$',
                        'EUR' => '€',
                        'GBP' => '£',
                    ];
                    $currencySymbol = $currencySymbols[$currency] ?? $currency;
                @endphp
                <p class="card__image__price-favourite__price__value article-card__image__price-favourite__price__value"
                    itemprop="price" content="{!! $price !!}">
                    {!! $price !!} <span itemprop="priceCurrency"
                        content="{!! $currency !!}">{!! $currencySymbol !!}</span><span
                        class="card__image__price-favourite__value__unit article-card__image__price-favourite__value__unit">/@lang('units.' . $per)</span>
                </p>
            </div>
            <div class="card__image__price-favourite__favourite article-card__image__price-favourite__favourite">
                @auth
                    @if (auth()->user()->favouriteArticles()->where('article_id', $article->id)->exists())
                        <x-button wire:click="removeArticleFromFavourite('{!! $article->id !!}')" style="none"
                            class="card__image__price-favourite__favourite__btn article-card__image__price-favourite__favourite__btn"
                            title="{!! __('buttons.remove_from_favourites') !!}">
                            <x-icons name="heart-filled" />
                        </x-button>
                    @else
                        <x-button wire:click="addArticleToFavourite('{!! $article->id !!}')" style="none"
                            class="card__image__price-favourite__favourite__btn article-card__image__price-favourite__favourite__btn"
                            title="{!! __('buttons.add_to_favourites') !!}">
                            <x-icons name="heart" />
                        </x-button>
                    @endif
                @endauth
                @guest
                    <x-button wire:click="redirectUser" style="none"
                        class="card__image__price-favourite__favourite__btn article-card__image__price-favourite__favourite__btn"
                        title="{!! __('buttons.add_to_favourites') !!}">
                        <x-icons name="heart" />
                    </x-button>
                @endguest
            </div>
        </div>
    </div>
    <div class="card__infos article-card__infos" itemprop="itemReviewed">
        <h3 class="card__infos__title article-card__infos__title" itemprop="name">{!! $article->name . ' (' . $article->variants()->first()->name . ')' !!}</h3>
        <div class="card__infos__variants article-card__infos__variants">
            <h4 class="card__infos__variants__title article-card__infos__variants__title">@lang('titles.variants') :</h4>
            <p class="card__infos__variants__list article-card__infos__variants__list">
                {{ $article->variants()->pluck('name')->implode(', ') }}
            </p>
        </div>
        <div itemprop="manufacturer" itemscope itemtype="https://schema.org/Organization">
            <span class="card__infos__shop article-card__infos__shop"
                itemprop="legalName">{!! $article->shop->name !!}</span><span class="hidden" {{-- TODO: change the shop url --}}
                itemprop="url">https://lokkalt.com/commerces/belgique/alcool/be-4880-val-dieu</span>
            <span class="card__infos__address article-card__infos__address"
                itemprop="address">{!! $article->shop->postal_code . ' - ' . $article->shop->city !!}</span>
        </div>
        <div class="card__infos__score article-card__infos__score" itemprop="aggregateRating" itemscope
            itemtype="https://schema.org/AggregateRating">
            <x-scores.stars :score="$article->scores_avg_rating" />
            <p class="hidden" itemprop="ratingValue">{!! $article->scores_avg_rating !!}</p>
            <p class="card__infos__score__count article-card__infos__score__count">
                (<span itemprop="reviewCount">{!! $article->scores_count !!}</span>)</p>
        </div>
    </div>
    <div class="card__btns article-card__btns">
        @if (auth()->user()->cart->articles->contains($article->id))
            <x-button event="remove-from-cart" eventTo="cart.cart-icon"
                eventAttr="{ articleId: '{!! $article->id !!}', variantId: '{!! $article->variants()->first()->id !!}' }"
                style="filled" class="card__btns__btn article-card__btns__btn">@lang('buttons.remove_from_cart')</x-button>
        @else
            <x-button event="add-to-cart" eventTo="cart.cart-icon"
                eventAttr="{ articleId: '{!! $article->id !!}', variantId: '{!! $article->variants()->first()->id !!}' }"
                style="outlined" class="card__btns__btn article-card__btns__btn">@lang('buttons.add_to_cart')</x-button>
        @endif
        <x-button style="outlined" class="card__btns__btn article-card__btns__btn">@lang('buttons.buy_now')</x-button>
    </div>
</div>
