<x-layouts.app>
    @section('pageTitle', $shop->name)
    <div x-data="{ open: false, url: '' }">
        <section class="shop single-page">
            <h2 class="hidden">{!! $shop->name !!} | {!! $shop->type !!}</h2>
            <div class="shop__top single-page__top">
                <div class="shop__top__images single-page__top__images">
                    @foreach ($shop->images as $index => $image)
                        <div class="shop__top__images__image single-page__top__images__image">
                            @if ($index === 0)
                                @php
                                    $url = Illuminate\Support\Facades\Cache::get('big_file_url_' . $image->url);

                                    if (!$url) {
                                        $url = Storage::disk('s3')->temporaryUrl(
                                            'web/big/' . $image->url,
                                            now()->addHours(10),
                                        );
                                        Illuminate\Support\Facades\Cache::put(
                                            'big_file_url_' . $image->url,
                                            $url,
                                            now()->addHours(10),
                                        );
                                    }
                                @endphp
                                <img width="730" height="448" src="{!! $url !!}"
                                    alt="Image du vendeur {!! $shop->name !!}" itemprop="image"
                                    @click="open = true; url = '{!! $url !!}'">
                            @else
                                @php
                                    $url = Illuminate\Support\Facades\Cache::get('medium_file_url_' . $image->url);
                                    $bigUrl = Illuminate\Support\Facades\Cache::get('big_file_url_' . $image->url);

                                    if (!$url) {
                                        $url = Storage::disk('s3')->temporaryUrl(
                                            'web/medium/' . $image->url,
                                            now()->addHours(10),
                                        );
                                        Illuminate\Support\Facades\Cache::put(
                                            'medium_file_url_' . $image->url,
                                            $url,
                                            now()->addHours(10),
                                        );
                                    }

                                    if (!$bigUrl) {
                                        $bigUrl = Storage::disk('s3')->temporaryUrl(
                                            'web/big/' . $image->url,
                                            now()->addHours(10),
                                        );
                                        Illuminate\Support\Facades\Cache::put(
                                            'big_file_url_' . $image->url,
                                            $url,
                                            now()->addHours(10),
                                        );
                                    }
                                @endphp
                                <img width="230" height="230" src="{!! $url !!}"
                                    alt="Image du vendeur {!! $shop->name !!}" itemprop="image"
                                    @click="open = true; url = '{!! $bigUrl !!}'">
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="shop__top__infos single-page__top__infos">
                    <div class="shop__top__infos__header single-page__top__infos__header">
                        <h2 class="shop__top__infos__header__title single-page__top__infos__header__title">
                            {!! $shop->name !!}</h2>
                        <p class="shop__top__infos__header__category">{!! $shop->type !!}</p>
                        <p class="shop__top__infos__header__address">{!! $shop->address !!}</p>
                        <div
                            class="shop__top__infos__header__score-favourite single-page__top__infos__header__score-favourite">
                            <div
                                class="shop__top__infos__header__score-favourite__score single-page__top__infos__header__score-favourite__score">
                                <x-scores.stars :score="$shop->scores_avg_score" />
                                <p class="card__infos__score__count article-card__infos__score__count">
                                    ({!! $shop->scores_count !!})</p>
                            </div>
                            <livewire:buttons.favourite-button :$shop />
                        </div>
                    </div>
                    <div class="shop__top__infos__description">
                        <h3 class="shop__top__infos__description__title">Description</h3>
                        <p class="shop__top__infos__description__content">{!! $shop->description !!}</p>
                    </div>
                </div>
            </div>
        </section>
        @if ($franchiseShops->isNotEmpty())
            <section class="shop-other-shops section">
                <h2 class="shop-other-shops__title section__sub-title">@lang('titles.other-shops-of') {!! $shop->name !!}</h2>
                <div class="shop-other-shops__list">
                    @foreach ($franchiseShops as $shp)
                        <x-cards.shop :shop="$shp" />
                    @endforeach
                </div>
            </section>
        @endif
        <section class="shop-articles section">
            <livewire:shop.shop-articles-list :$shop lazy="on-load" wire:key="{!! $shop->id !!}" />
        </section>
        <div class="modal-container" x-cloak x-show="open" x-transition.opacity>
            <div class="modal single-page__modal" @click.away="open = false">
                <div class="modal__top">
                    <h3>@lang('titles.image')</h3>
                    <x-icon class="modal-close-button" name="heroicon-o-x-mark" @click="open = false" />
                </div>
                <div class="modal__body single-page__modal__image">
                    <img :src="url" alt="">
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
