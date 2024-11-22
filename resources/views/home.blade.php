<x-layouts.app>
    <section class="intro">
        <h2 class="intro__title hidden">{!! __('titles.intro_welcome') !!}</h2>
        <div class="intro__left" itemscope itemtype="https://schema.org/Organization">
            <p class="intro__left__title" itemprop="slogan">@lang('home.intro')</p>
            <p class="intro__left__explanation">@lang('home.intro_explanation')</p>
            <p class="intro__left__explanation">@lang('home.intro_explanation_bottom')</p>
            <p class="intro__left__explanation" id="status"></p>
            <a hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.sell-on-lokkalt') !!}"><x-button>@lang('buttons.create_your_shop')</x-button></a>
        </div>
        <div class="intro__right">
            <div class="intro__right__image">
                <img width="700" height="460" src="{!! asset('storage/img/intro.webp') !!}"
                    alt="Image d’introduction de Lokkalt représentant une étagère avec des bocaux de produits locaux">
                <div class="intro__right__background"></div>
            </div>
        </div>
    </section>
    <section class="popular-categories">
        <div class="popular-categories__top section__top">
            <h2 class="popular-categories__top__title section__title-nm">@lang('titles.popular_categories')</h2>
            <a class="arrow-link section__see-more" href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.categories') !!}">@lang('titles.see_all_categories') <x-icons
                    name="arrow" /></a>
        </div>
        <livewire:categories.quick-categories-list lazy="on-load" />
    </section>
    <section class="quick-articles">
        <div class="quick-articles__top section__top">
            <h2 class="quick-articles__top__title section__title-nm">@lang('titles.articles_to_see')</h2>
            <a class="arrow-link section__see-more" href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.articles') !!}">@lang('titles.see_all_articles') <x-icons
                    name="arrow" /></a>
        </div>
        <livewire:articles.quick-articles-list lazy="on-load" />
    </section>
    <section class="popular-shops">
        <div class="popular-shops__top section__top">
            <h2 class="popular-shops__top__title section__title-nm">@lang('titles.popular_shops')</h2>
            <a class="arrow-link section__see-more" href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shops') !!}">@lang('titles.see_all_shops') <x-icons
                    name="arrow" /></a>
        </div>
        <livewire:shops.quick-shops-list lazy="on-load" />
    </section>
</x-layouts.app>
