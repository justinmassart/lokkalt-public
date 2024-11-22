<x-layouts.app>
    @section('pageTitle', $article->name)
    <div x-data="{ open: false, url: '' }">
        <section class="article single-page" itemscope itemtype="https://schema.org/Product">
            <h2 class="hidden">@lang('titles.about_article') : {!! $article->name !!}</h2>
            <section class="article__top single-page__top">
                @php
                    if ($variant) {
                        $variantImages = $variant->images()->orderByDesc('is_main_image')->pluck('url')->toArray();
                        $articleImages = $article->images()->orderByDesc('is_main_image')->pluck('url')->toArray();
                        $images = [];

                        if (count($variantImages) < 4) {
                            $images = array_merge(
                                $variantImages,
                                array_slice($articleImages, 0, 4 - count($variantImages)),
                            );
                        } else {
                            $images = array_slice($variantImages, 0, 4);
                        }
                    } else {
                        $images = $article->images()->orderByDesc('is_main_image')->pluck('url')->toArray();
                    }
                @endphp
                <div class="article__top__images single-page__top__images">
                    @foreach ($images as $index => $i)
                        <div class="article__top__images__image single-page__top__images__image">
                            @php
                                $url = Illuminate\Support\Facades\Cache::get('big_file_url_' . $i);

                                if (!$url) {
                                    $url = Storage::disk('s3')->temporaryUrl('web/big/' . $i, now()->addHours(10));
                                    Illuminate\Support\Facades\Cache::put(
                                        'big_file_url_' . $i,
                                        $url,
                                        now()->addHours(10),
                                    );
                                }
                            @endphp
                            @if ($index === 0)
                                <img width="730" height="448" src="{!! $url !!}"
                                    alt="Image du produit {!! $article->name !!} du vendeur {!! $shop->name !!}"
                                    itemprop="image" @click="open = true; url = '{!! $url !!}'">
                            @else
                                @php
                                    $url = Illuminate\Support\Facades\Cache::get('medium_file_url_' . $i);
                                    $bigUrl = Illuminate\Support\Facades\Cache::get('big_file_url_' . $i);

                                    if (!$url) {
                                        $url = Storage::disk('s3')->temporaryUrl(
                                            'web/medium/' . $i,
                                            now()->addHours(10),
                                        );
                                        Illuminate\Support\Facades\Cache::put(
                                            'medium_file_url_' . $i,
                                            $url,
                                            now()->addHours(10),
                                        );
                                    }

                                    if (!$bigUrl) {
                                        $bigUrl = Storage::disk('s3')->temporaryUrl(
                                            'web/big/' . $i,
                                            now()->addHours(10),
                                        );
                                        Illuminate\Support\Facades\Cache::put(
                                            'big_file_url_' . $i,
                                            $url,
                                            now()->addHours(10),
                                        );
                                    }
                                @endphp
                                <img width="230" height="230" src="{!! $url !!}"
                                    alt="Image du produit {!! $article->name !!} du vendeur {!! $shop->name !!}"
                                    itemprop="image" @click="open = true; url = '{!! $bigUrl !!}'">
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="article__top__infos single-page__top__infos">
                    <div class="article__top__infos__header single-page__top__infos__header" itemprop="itemReviewed">
                        <h2 class="article__top__infos__header__title single-page__top__infos__header__title"
                            itemprop="name">{!! $article->name !!} @if ($variant)
                                <span
                                    class="article__top__infos__header__title__variant single-page__top__infos__header__title__variant">{!! $variant->name !!}</span>
                            @endif
                        </h2>
                        <div
                            class="article__top__infos__header__score-favourite single-page__top__infos__header__score-favourite">
                            <div class="article__top__infos__header__score-favourite__score single-page__top__infos__header__score-favourite__score"
                                itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                <x-scores.stars :score="$article->scores_avg_score" />
                                <p class="hidden" itemprop="ratingValue">{!! $article->scores_count !!}</p>
                                <p class="card__infos__score__count article-card__infos__score__count">
                                    (<span itemprop="reviewCount">{!! $article->scores_count !!}</span>)</p>
                            </div>
                            <livewire:buttons.favourite-button :$article wire:key="{!! $article->id . '-' . ($variant ? $variant->id : str()->random(5)) !!}" />
                        </div>
                    </div>
                    @if ($variant)
                        <div class="article__top__infos__price">
                            @php
                                $priceModel =
                                    $variant->prices()->where('currency', session('currency'))->first() ??
                                    $variant->prices()->first();
                                $price = $priceModel->price;
                                $currency = $priceModel->currency;
                                $per = $priceModel->per;

                                $currencySymbols = [
                                    'USD' => '$',
                                    'EUR' => '€',
                                    'GBP' => '£',
                                ];
                                $currencySymbol = $currencySymbols[$currency] ?? $currency;
                            @endphp
                            <p class="article__top__infos__price__value" itemprop="price"
                                content="{!! $price !!}">
                                {!! $price !!} <span itemprop="priceCurrency"
                                    content="{!! $currency !!}">{!! $currencySymbol !!}</span><span
                                    class="article__top__infos__price__unit">/@lang('units.' . $per)</span></p>
                            <x-assets.stock-status :stock_status="$shopArticle->stock->status" />
                        </div>
                    @endif
                    <div class="article__top__infos__variants">
                        <h3 class="article__top__infos__variants__title">@lang('titles.variants')</h3>
                        <ul class="article__top__infos__variants__list">
                            @foreach ($article->variants as $v)
                                <li class="article__top__infos__variants__list__item">
                                    <x-cards.variant wire:key="{!! $v->id !!}" :variant="$v"
                                        link="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.article', [
                                            'shop' => $shop->slug,
                                            'article' => $article->slug,
                                            'variant' => $v->slug,
                                        ]) !!}" />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if ($shopArticle && $shopArticle->stock && $shopArticle->stock->status !== 'out')
                        <livewire:cart.cart-amount :$shopArticle wire:key="{!! $article->reference . '-' . $variant->reference !!}" />
                    @endif
                </div>
            </section>
            <section class="article__tabs" x-data="{ details: true, likes: false, questions: false }">
                <h3 class="hidden">@lang('titles.more_informations_about_article') : {!! $article->name !!}</h3>
                <div class="article__tabs__header">
                    <div class="article__tabs__header__link section__sub-title"
                        @click="details = true; likes = false; questions = false;">
                        <p class="link" :class="{ 'active': details }">@lang('titles.details')</p>
                    </div>
                    <div class="article__tabs__header__separator"></div>
                    <div class="article__tabs__header__link section__sub-title"
                        @click="details = false; likes = true; questions = false;">
                        <p class="link" :class="{ 'active': likes }">@lang('titles.appreciations')</p>
                    </div>
                    <div class="article__tabs__header__separator"></div>
                    <div class="article__tabs__header__link section__sub-title"
                        @click="details = false; likes = false; questions = true;">
                        <p class="link" :class="{ 'active': questions }">@lang('titles.questions')</p>
                    </div>
                </div>
                <section class="article__tabs__details" x-show="details" x-transition.opacity>
                    <h4 class="hidden">@lang('titles.details')</h4>
                    <div class="article__tabs__details__description">
                        <h5 class="article__tabs__details__description__title article__tabs__sub-title">
                            @lang('titles.description')
                        </h5>
                        <div class="card-na article-description-card">
                            <p class="article-description-card__text" itemprop="description">{!! $variant->description ?? $article->description !!}
                            </p>
                        </div>
                    </div>
                    <div class="article__tabs__details__article-details">
                        <h5 class="article__tabs__details__article-details__title article__tabs__sub-title">
                            @lang('titles.details_of_articles')</h5>
                        @php
                            $variantDetails = $variant ? $variant->details : [];
                            $articleDetails = $article->details ?? [];
                            $details = array_merge($variantDetails, $articleDetails);
                            ksort($details);
                        @endphp
                        <x-articles.details :details="$details" />
                    </div>
                    <div class="article__tabs__details__article-seller">
                        <h5 class="article__tabs__details__article-seller__title article__tabs__sub-title">
                            @lang('titles.details_of_seller')</h5>
                        <x-cards.shop :$shop />
                    </div>
                </section>
                <section class="article__tabs__appreciations" x-show="likes" x-transition.opacity>
                    <h4 class="hidden">@lang('titles.appreciations')</h4>
                    <livewire:scores.comments-list :$article :$shop lazy="on-load"
                        wire:key="{!! $article->reference . '-' . ($variant ? $variant->reference : str()->random(5)) . str()->random(5) !!}" />
                    <div class="article__tabs__appreciations__score">
                        <h5 class="article__tabs__appreciations__score__title article__tabs__sub-title">
                            @lang('titles.global_scores')
                        </h5>
                        <livewire:scores.score-board :$article lazy="on-load" />
                    </div>
                </section>
                <section class="article__tabs__questions" x-show="questions" x-transition.opacity>
                    <livewire:questions.questions-list :$article :$shop lazy="on-load"
                        wire:key="{!! $article->id . '-' . ($variant ? $variant->id : str()->random(5)) . str()->random(5) !!}" />
                </section>
            </section>
        </section>
        <section class="merchant-other-products">
            <livewire:articles.merchant-others-articles :$shop :$article lazy="on-load" />
        </section>
        <section class="similar-products">
            <livewire:articles.similar-articles :$shop :$article lazy="on-load" />
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
