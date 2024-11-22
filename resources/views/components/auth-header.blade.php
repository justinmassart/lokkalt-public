<header class="header" itemscope itemtype="https://schema.org/Organization">
    <h1 class="hidden" itemprop="legalName">Lokkalt</h1>
    <nav class="header__top">
        <h2 class="hidden">@lang('header.second_navigation')</h2>
        <div class="header__top__logo" itemprop="logo">
            <a class="card-link" title="{!! __('links.home') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                href="/">Lokkalt</a>
            <x-icons name="logo" />
        </div>
        <div class="header__top__burger">
            <div class="header__top__burger__item"></div>
            <div class="header__top__burger__item"></div>
        </div>
        <div class="header__top__menu">
            <div class="header__top__menu__locale" x-data="{ open: false }">
                <div class="header__top__menu__locale__btn" @click.prevent="open = !open">
                    <x-icons name="{!! strtolower(explode('-', LaravelLocalization::getCurrentLocale())[1]) !!}" folder="flags" />
                </div>
                <div class="header__top__menu__locale__container card-na" x-cloak x-show="open"
                    @click.away="open = false" x-transition.opacity>
                    <ul class="header__top__menu__locale__container__langs-list">
                        @foreach (config('locales.supportedLanguages') as $lang => $properties)
                            <li class="header__top__menu__locale__container__langs-list__item">
                                <a class="link {!! strtolower(explode('-', LaravelLocalization::getCurrentLocale())[0]) === $lang ? 'link-active' : '' !!}" rel="alternate"
                                    hreflang="{!! $lang !!}"
                                    href="{{ LaravelLocalization::getLocalizedURL($lang . '-' . explode('-', LaravelLocalization::getCurrentLocale())[1], null, [], true) }}">
                                    {!! $lang !!}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <ul class="header__top__menu__locale__container__countries-list">
                        @foreach (config('locales.supportedCountries') as $country => $prop)
                            <li class="header__top__menu__locale__container__countries-list__item">
                                <a class="link {!! strtoupper(explode('-', LaravelLocalization::getCurrentLocale())[1]) === $country ? 'link-active' : '' !!}" rel="alternate"
                                    hreflang="{!! explode('-', LaravelLocalization::getCurrentLocale())[0] !!}"
                                    href="{{ LaravelLocalization::getLocalizedURL(
                                        explode('-', LaravelLocalization::getCurrentLocale())[0] . '-' . $country,
                                        null,
                                        [],
                                        true,
                                    ) }}">
                                    {{ App\Helpers\FlagEmoji::countryToFlag($country) }} @lang('countries.' . str()->slug($prop['name']))
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <livewire:header.search-bar />
            <div class="header__top__menu__profile">
                <div class="header__top__menu__profile__cart">
                    <a class="card-link"
                        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.cart') }}">@lang('header.cart')</a>
                    <livewire:cart.cart-icon />
                </div>
                {{-- <div class="header__top__menu__profile__notification">
                    <a class="card-link"
                        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-notifications') }}">@lang('header.my_notifications')</a>
                    <span class="header__top__menu__profile__notification__number notification-number">7</span>
                    <x-icons name="bell" />
                </div> --}}
                <div class="header__top__menu__profile__avatar" x-data="{ open: false }">
                    <x-icons livewire="@click='open = !open'" name="avatar" />
                    <div class="header__top__menu__profile__avatar__dropdown" x-cloak x-show="open"
                        :class="{ 'open': open, 'closed': !open }" @click.away="open = false">
                        <h3 class="hidden">@lang('header.my_links')</h3>
                        <ul>
                            @if (auth()->user()->isSeller())
                                <li>
                                    <a class="link" title="{!! __('links.dashboard_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                                        href="https://{!! config('app.domains.dashboard') !!}/">@lang('header.dashboard')</a>
                                </li>
                            @endif
                            <li>
                                <a class="link" title="{!! __('links.order_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-orders') }}">@lang('header.my_orders')</a>
                            </li>
                            <li>
                                <a class="link" title="{!! __('links.favourite_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-favourites') }}">@lang('header.my_favourites')</a>
                            </li>
                            <li>
                                <a class="link" title="{!! __('links.profile_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-profile') }}">@lang('header.my_profile')</a>
                            </li>
                            @if (true === false)
                                <li>
                                    <a class="link" title="{!! __('links.settings_title') !!}"
                                        hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                                        href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-settings') }}">@lang('header.my_settings')</a>
                                </li>
                            @endif
                        </ul>
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit" class="logout-btn link-danger">@lang('header.logout')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <nav class="header__bottom" x-data="{ open: false }">
        <h2 class="hidden">@lang('header.primary_navigation')</h2>
        <ul class="header__bottom__nav">
            <li class="header__bottom__nav__item">
                <a @click.prevent="open = !open" class="link" title="{!! __('links.categories_title') !!}"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#">@lang('header.categories')
                    <span class="header__bottom__nav__item__chervron chevron down"
                        :class="{ 'up': open, 'down': !open }"><x-icons name="chevron" /></span></a>
            </li>
            {{-- <li class="header__bottom__nav__item">
                <a class="link" title="{!! __('links.around_me_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#">Autour de moi</a>
            </li> --}}
            <li class="header__bottom__nav__item">
                <a class="link" title="{!! __('links.articles_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.articles') }}">@lang('header.articles')</a>
            </li>
            <li class="header__bottom__nav__item">
                <a class="link" title="{!! __('links.shops_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shops') }}">@lang('header.shops')</a>
            </li>
        </ul>
        <ul class="header__bottom__links">
            <li class="header__bottom__links__item">
                <a class="link" title="{!! __('links.sell_on_lokkalt_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.sell-on-lokkalt') !!}">@lang('header.sell_on_lokkalt')</a>
            </li>
            <li class="header__bottom__links__item">
                <a class="link" title="{!! __('links.support_title') !!}" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.support') !!}">@lang('header.support')</a>
            </li>
        </ul>
        <livewire:header.categories-list lazy />
    </nav>
</header>
