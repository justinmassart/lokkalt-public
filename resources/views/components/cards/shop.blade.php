<div class="card shop-card shop-card-6C" itemscope itemtype="https://schema.org/Organization">
    <a class="card-link" title="{!! __('links.shop') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shop', [
        'shop' => $shop->slug,
    ]) !!}"
        itemprop="url">{!! __('links.shop', ['shop_name' => $shop->name]) !!}</a>
    <div class="card__container shop-card__container-top shop-card-6C__container">
        @php
            $im = $shop->images()->where('is_main_image')->first()
                ? $shop->images()->where('is_main_image')->first()->url
                : $shop->images()->first()->url;

            $url = Illuminate\Support\Facades\Cache::get('medium_file_url_' . $im);
            if (!$url) {
                $url = Storage::disk('s3')->temporaryUrl('web/medium/' . $im, now()->addHours(10));
                Illuminate\Support\Facades\Cache::put('medium_file_url_' . $im, $url, now()->addHours(10));
            }
        @endphp
        <div class="card__image shop-card__image">
            <img width="250" height="250" src="{!! $url !!}" alt="{!! __('image.description', ['subject' => $shop->name]) !!}"
                itemprop="image">
        </div>
        <div class="card__infos shop-card__infos" itemprop="itemReviewed">
            <h3 class="shop-card__infos__name" itemprop="name">{!! $shop->name !!}</h3>
            <p class="shop-card__infos__category">{!! $shop->type !!}</p>
            <p class="shop-card__infos__address">{!! $shop->address !!}</p>
            <div class="hidden" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
                <span class="hidden" itemprop="streetAddress">{!! $shop->address !!}</span>
                <span class="hidden" itemprop="addressCountry">{!! $shop->country !!}</span>
                <span class="hidden" itemprop="addressRegion">{!! $shop->city !!}</span>
                <span class="hidden" itemprop="postalCode">{!! $shop->postal_code !!}</span>
            </div>
            <p class="shop-card__infos__articles-count">{!! $shop->articles_count !!} @lang('titles.articles_displayed')</p>
        </div>
    </div>
    <div class="card__container shop-card__container-bottom shop-card-6C__container mt-2">
        <div class="card__infos__score shop-card__infos__score" itemprop="aggregateRating" itemscope
            itemtype="https://schema.org/AggregateRating">
            <x-scores.stars :score="$shop->scores_avg_score" />
            <p class="hidden" itemprop="ratingValue">{!! $shop->scores_avg_score !!}</p>
            <p class="card__infos__score__count shop-card__infos__score__count">(<span
                    itemprop="reviewCount">{!! $shop->scores_count !!}</span>)</p>
        </div>
        @php
            /* date_default_timezone_set(session('locationData')['timezone']);

            $currentDay = strtolower(date('l'));
            $openingHoursToday = $shop->opening_hours[$currentDay];
            $currentTime = strtotime(date('H:i'));
            $isOpen = false;

            foreach ($openingHoursToday as $interval) {
                $start = strtotime($interval['from']);
                $end = strtotime($interval['to']);

                if ($currentTime >= $start && $currentTime <= $end) {
                    $isOpen = true;
                    break;
                }
            } */
        @endphp
        {{-- <div class="card__infos__shop-status shop-card__infos__shop-status">
            <p>{{ $isOpen ? 'Actuellement ouvert' : 'Actuellement fermé' }}</p>
        </div> --}}
    </div>
</div>
