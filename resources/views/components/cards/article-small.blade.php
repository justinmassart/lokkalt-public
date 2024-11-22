<div wire:key="{!! $key !!}" class="card article-card article-card-3C" itemscope
    itemtype="https://schema.org/Product">
    <a class="card-link" title="{!! __('links.article') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.article', ['shop' => $shop->slug, 'article' => $article->slug, 'variant' => $variant->slug]) }}"
        itemprop="url">{!! __('links.article', ['article' => $article->name]) !!}</a>
    <div class="card__image article-card__image">
        @php
            $variantMainImage = $variant ? $variant->images()->where('is_main_image', true)->first() : null;
            $variantImage = $variant ? $variant->images()->first() : null;
            if ($variantMainImage) {
                $image = $variantMainImage->url;
                return;
            } elseif ($variantImage) {
                $image = $variantImage->url;
                return;
            }

            $image = $article->images()->first()->url;
        @endphp
        @php
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
                        class="card__image__price-favourite__value__unit article-card__image__price-favourite__value__unit">/@lang('units.' . $per)</span>
                </p>
            </div>
            <livewire:articles.add-to-favourite-button :$article :$variant wire:key="{!! $article->id . '-' . $variant->id !!}" />
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
            <a class="link-ns card__infos__shop article-card__infos__shop" title="{!! __('links.shop') !!}"
                hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#"><span
                    itemprop="legalName">{!! $shop->name !!}</span><span class="hidden"
                    itemprop="url">{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shop', [
                        'shop' => $shop->slug,
                    ]) !!}</span></a>
            <a class="link-ns card__infos__address article-card__infos__address" title="{!! __('links.address') !!}"
                hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#"><span
                    itemprop="address">{!! $shop->address !!}</span></a>
        </div>
        <div class="card__infos__score article-card__infos__score" itemprop="aggregateRating" itemscope
            itemtype="https://schema.org/AggregateRating">
            <x-scores.stars :score="$article->scores_avg_rating" />
            <p class="hidden" itemprop="ratingValue">{!! $article->scores_avg_rating !!}</p>
            <p class="card__infos__score__count article-card__infos__score__count">(<span
                    itemprop="reviewCount">{!! $article->scores_count !!}</span>)</p>
        </div>
    </div>
    <livewire:articles.add-to-cart-button :$variant :$article wire:key="{!! $article->id . '-' . $variant->id !!}" />
</div>
