<div wire:key="{!! $key !!}" class="card shop-card shop-card-4C" itemscope
    itemtype="https://schema.org/Organization">
    <a class="card-link" title="{!! __('links.shop') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shop', ['shop' => $shop->slug]) }}"
        itemprop="url">{!! __('links.shop', ['shop_name' => $shop->name]) !!}</a>
    <div class="card__image shop-card__image">
        @php
            $mainImage = $shop->images()->where('is_main_image', true)->first();
            $image = $mainImage
                ? $mainImage->url
                : ($shop->images()->count() > 0
                    ? $shop->images()->first()->url
                    : null);

            $url = Illuminate\Support\Facades\Cache::get('small_file_url_' . $image);
            if (!$url) {
                $url = Storage::disk('s3')->temporaryUrl('web/small/' . $image, now()->addHours(10));
                Illuminate\Support\Facades\Cache::put('small_file_url_' . $image, $url, now()->addHours(10));
            }
        @endphp
        <img width="135" height="135" src="{!! $url !!}" alt="{!! __('image.description', ['subject' => $shop->name]) !!}" itemprop="image">
    </div>
    <div class="card__infos shop-card__infos" itemprop="itemReviewed">
        <h3 class="shop-card__infos__name" itemprop="name">{!! $shop->name !!}</h3>
        <p class="shop-card__infos__category">{!! $shop->type !!}</p>
        <p class="shop-card__infos__address">{!! $shop->postal_code . ' - ' . $shop->city !!}</p>
        <div class="hidden" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
            <span class="hidden" itemprop="streetAddress">{!! $shop->address !!}</span>
            <span class="hidden" itemprop="addressCountry">{!! $shop->country !!}</span>
            <span class="hidden" itemprop="addressLocality">{!! $shop->city !!}</span>
            <span class="hidden" itemprop="addressRegion">{!! $shop->city !!}</span>
            <span class="hidden" itemprop="postalCode">{!! $shop->postal_code !!}</span>
        </div>
        <div class="card__infos__score shop-card__infos__score" itemprop="aggregateRating" itemscope
            itemtype="https://schema.org/AggregateRating">
            <x-scores.stars :score="$shop->scores_avg_score" />
            <p class="hidden" itemprop="ratingValue">{!! $shop->scores_avg_score !!}</p>
            <p class="card__infos__score__count shop-card__infos__score__count">(<span
                    itemprop="reviewCount">{!! $shop->scores_count !!}</span>)</p>
        </div>
    </div>
</div>
