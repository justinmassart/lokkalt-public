<x-layouts.app>
    @section('pageTitle', $shop->name)
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
                                        now()->addMinutes(2),
                                    );
                                    Illuminate\Support\Facades\Cache::put(
                                        'big_file_url_' . $image->url,
                                        $url,
                                        now()->addMinutes(2),
                                    );
                                }
                            @endphp
                            <img width="730" height="448" src="{!! $url !!}"
                                alt="Image du vendeur {!! $shop->name !!}" itemprop="image">
                        @else
                            @php
                                $url = Illuminate\Support\Facades\Cache::get('medium_file_url_' . $image->url);

                                if (!$url) {
                                    $url = Storage::disk('s3')->temporaryUrl(
                                        'web/medium/' . $image->url,
                                        now()->addMinutes(2),
                                    );
                                    Illuminate\Support\Facades\Cache::put(
                                        'medium_file_url_' . $image->url,
                                        $url,
                                        now()->addMinutes(2),
                                    );
                                }
                            @endphp
                            <img width="230" height="230" src="{!! $url !!}"
                                alt="Image du vendeur {!! $shop->name !!}" itemprop="image">
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
                            <x-scores.stars :score="$shop->scores_avg_rating" />
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
    {{-- <section class="shop-articles-categories section">
        <div class="shop-articles-categories__header section__header">
            <h2 class="shop-articles-categories__title section__sub-title">Catégories d’articles</h2>
        </div>
        <div class="shop-articles-categories__list">
            @foreach ($categories as $index => $category)
                <div class="card category-card category-card-2C-small">
                    <div class="card__image category-card__image category-card-2C-small__image">
                        <div class="card__image__gradient category-card__image__gradient"></div>
                        <img width="230" height="230"
                            src="{{ asset('storage/img/categories/' . str()->slug($category->name) . '.webp') }}"
                            alt="{!! __('image.description', ['subject' => $category->name]) !!}">
                    </div>
                    <div class="card__name category-card__name
                        category-card-2C-small__name">
                        <h3 class="card__name__title category-card__name__title category-card-2C-small__name">
                            {!! $category->name !!}
                        </h3>
                    </div>
                </div>
            @endforeach
        </div>
    </section> --}}
    <section class="shop-articles section">
        <livewire:shop.shop-articles-list :shop="$shop" lazy="on-load" wire:key="{!! $shop->id !!}" />
    </section>
    {{--     <section class="shop-events section">
        <div class="shop-events__header section__header">
            <h2 class="section__sub-title shop-events__title">Événements de Lépieds</h2>
            <div class="section__filters">
                <x-button style="outlined">@lang('buttons.filter')</x-button>
                <x-button style="outlined">@lang('buttons.sort')</x-button>
            </div>
        </div>
        <div class="shop-events__list">
            <x-shops.event />
            <x-shops.event />
            <x-shops.event />
        </div>
    </section> --}}
    {{--  <section class="shop-notifications section">
        <div class="shop-notifications__header section__header">
            <h2 class="section__sub-title shop-notifications__title">Recevoir des notifications</h2>
        </div>
        <div class="shop-notifications__intro">
            Vous pouvez choisir de recevoir des notifications pour ce commerce, par exemple lorsqu’il ajoute des
            articles, change des prix et d’autres types de changement.
        </div>
        <form class="shop-notifications__form">
            <div class="shop-notifications__form__field form__field">
                <label class="shop-notifications__form__label form__field__label" for="shop-notification">Email / N°
                    de téléphone</label>
                <input class="shop-notifications__form__input form__field__input" type="email"
                    name="shop_notifications" id="shop-notification" placeholder="{!! __('inputs.email_placeholder') !!}">
            </div>
            <x-button>Recevoir des notifications</x-button>
        </form>
    </section> --}}
</x-layouts.app>
