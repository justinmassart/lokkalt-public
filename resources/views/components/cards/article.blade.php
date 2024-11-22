<div wire:key="{!! $key !!}" class="card article-card article-card-4C" itemscope
    itemtype="https://schema.org/Product">
    <a class="card-link" title="{!! __('links.article') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.article', ['shop' => $shop->slug, 'article' => $article->slug, 'variant' => $variant->slug]) }}"
        itemprop="url">{!! __('links.article', ['article' => $article->name]) !!}</a>
    <div class="card__image article-card__image">
        @php
            $variantMainImage = $variant->images()->where('is_main_image', true)->first();
            $variantImage = $variant->images()->first();

            if (!$variantMainImage && !$variantImage) {
                $img = $article->images()->where('is_main_image', true)->first();
                $image = $img ? $img->url : $article->images()->first()->url;
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
                        $variant->prices()->where('currency', session('currency'))->first() ??
                        $variant->prices()->first();
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
                        class="card__image__price-favourite__value__unit article-card__image__price-favourite__value__unit">/@lang('units.' . $unit)</span>
                </p>
            </div>
            <livewire:articles.add-to-favourite-button :$variant :$article wire:key="{!! $article->reference . '-' . $variant->reference . str()->random(5) !!}" />
        </div>
    </div>
    <div class="card__infos article-card__infos" itemprop="itemReviewed">
        <h3 class="card__infos__title article-card__infos__title" itemprop="name">{!! $article->name . ' (' . $variant->name . ')' !!}</h3>
        <div class="card__infos__variants article-card__infos__variants">
            {{-- <h4 class="card__infos__variants__title article-card__infos__variants__title">@lang('titles.variants') :</h4> --}}
            <p class="card__infos__variants__list article-card__infos__variants__list">
                {{ $article->variants()->pluck('name')->implode(', ') }}
            </p>
        </div>
        <div itemprop="manufacturer" itemscope itemtype="https://schema.org/Organization">
            <span class="card__infos__shop article-card__infos__shop"
                itemprop="legalName">{!! $shop->name !!}</span><span class="hidden" {{-- TODO: change the shop url --}}
                itemprop="url">{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shop', [
                    'shop' => $shop->slug,
                ]) !!}</span>
            <span class="card__infos__address article-card__infos__address"
                itemprop="address">{!! $shop->postal_code . ' - ' . $shop->city !!}</span>
        </div>
        <div class="card__infos__score article-card__infos__score" itemprop="aggregateRating" itemscope
            itemtype="https://schema.org/AggregateRating">
            <x-scores.stars :score="$article->scores_avg_score" />
            <p class="hidden" itemprop="ratingValue">{!! $article->scores_avg_score !!}</p>
            <p class="card__infos__score__count article-card__infos__score__count">
                (<span itemprop="reviewCount">{!! $article->scores_count !!}</span>)</p>
        </div>
    </div>
    <livewire:articles.add-to-cart-button :$variant :$article :$shop wire:key="{!! $article->reference . '-' . $variant->reference . str()->random(5) !!}" />
</div>
