<div class="header__categories" x-show="open" :class="{ 'closed': !open, 'open': open }">
    <h3 class="header__categories__title">@lang('titles.category_of_articles')</h3>
    <ul class="header__categories__list">
        @foreach ($this->categories as $category)
            <li class="header__categories__list__item">
                <a class="header__categories__list__item__link link" title="{!! $category !!}"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.category', ['category' => str()->slug($category)]) }}">@lang('categories.' . $category)</a>
            </li>
        @endforeach
    </ul>
</div>
