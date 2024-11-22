<div class="header__top__menu__search" x-data="{ show: @entangle('show') }">
    <form class="header__top__menu__search__input input">
        <label class="input__label" for="header-menu-search">Rechercher</label>
        <input wire:model.live.debounce.500ms='search' class="input__input" type="search" id="header-menu-search"
            name="menu_search" placeholder="{!! __('inputs.search-placeholder') !!}">
        <div class="input__svg">
            <x-icons name="search" />
        </div>
    </form>
    <div class="header__top__menu__search__results" x-cloak x-show="show"
        @click.away="show = false; $wire.emptyForm()">
        @if (count($this->articlesResults) > 0)
            <div>
                <p class="header__top__menu__search__results__title">@lang('titles.results_for_articles')</p>
                <ul class="header__top__menu__search__results__list">
                    @foreach ($this->articlesResults as $article)
                        <li class="header__top__menu__search__results__list__item">
                            <a class="search__link card-link" href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.article', [
                                'shop' => $article->shopArticles->first()->shop->slug,
                                'article' => $article->slug,
                                'variant' => $article->shopArticles->first()->variant->slug,
                            ]) !!}">{!! $article->name !!}</a>
                            <div class="header__top__menu__search__results__list__item__content">
                                <div class="header__top__menu__search__results__list__item__content__image">
                                    @php
                                        $check = $article->images()->where('is_main_image', true)->exists();

                                        $url = '';

                                        if ($check) {
                                            $url = $article->images()->where('is_main_image', true)->first()->url;
                                        } else {
                                            $url = $article->images()->first()->url;
                                        }

                                        $smallUrl = Illuminate\Support\Facades\Cache::get('small_file_url_' . $url);

                                        if (!$smallUrl) {
                                            $smallUrl = Storage::disk('s3')->temporaryUrl(
                                                'web/small/' . $url,
                                                now()->addHours(10),
                                            );
                                            Illuminate\Support\Facades\Cache::put(
                                                'small_file_url_' . $url,
                                                $smallUrl,
                                                now()->addHours(10),
                                            );
                                        }
                                    @endphp
                                    <img src="{!! $smallUrl !!}" alt="">
                                </div>
                                <div class="header__top__menu__search__results__list__item__content__infos">
                                    <div
                                        class="header__top__menu__search__results__list__item__content__infos__primary">
                                        <p>{!! $article->name !!}</p>
                                        <p>{!! $article->shopArticles->first()->variant->name !!}</p>
                                    </div>
                                    <div
                                        class="header__top__menu__search__results__list__item__content__infos__secondary">
                                        <p>{!! $article->shopArticles->first()->shop->name !!}</p>
                                        <p>{!! $article->shopArticles->first()->shop->postal_code !!} - {!! $article->shopArticles->first()->shop->city !!}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="header__top__menu__search__results__title">
                @lang('titles.no_results_for_articles')
            </p>
        @endif
        @if (count($this->shopResults) > 0)
            <div>
                <p class="header__top__menu__search__results__title">@lang('titles.results_for_shops')</p>
                <ul class="header__top__menu__search__results__list">
                    @foreach ($this->shopResults as $shop)
                        <li class="header__top__menu__search__results__list__item">
                            <a class="search__link card-link"
                                href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shop', [
                                    'shop' => $shop->slug,
                                ]) !!}">{!! $shop->name !!}</a>
                            <div class="header__top__menu__search__results__list__item__content">
                                <div class="header__top__menu__search__results__list__item__content__image">
                                    @php
                                        $check = $shop->images()->where('is_main_image', true)->exists();

                                        $url = '';

                                        if ($check) {
                                            $url = $shop->images()->where('is_main_image', true)->first()->url;
                                        } else {
                                            $url = $shop->images()->first()->url;
                                        }

                                        $smallUrl = Illuminate\Support\Facades\Cache::get('small_file_url_' . $url);

                                        if (!$smallUrl) {
                                            $smallUrl = Storage::disk('s3')->temporaryUrl(
                                                'web/small/' . $url,
                                                now()->addHours(10),
                                            );
                                            Illuminate\Support\Facades\Cache::put(
                                                'small_file_url_' . $url,
                                                $smallUrl,
                                                now()->addHours(10),
                                            );
                                        }
                                    @endphp
                                    <img src="{!! $smallUrl !!}" alt="">
                                </div>
                                <div class="header__top__menu__search__results__list__item__content__infos">
                                    <div
                                        class="header__top__menu__search__results__list__item__content__infos__primary">
                                        <p>{!! $shop->name !!}</p>
                                    </div>
                                    <div
                                        class="header__top__menu__search__results__list__item__content__infos__secondary">
                                        <p>{!! $shop->postal_code !!} - {!! $shop->city !!}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="header__top__menu__search__results__title">
                @lang('titles.no_results_for_shops')
            </p>
        @endif
    </div>
</div>
