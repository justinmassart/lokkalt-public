<footer class="footer">
    <div class="footer__go-back-up">
        <a class="footer__go-back-up__link" title="@lang('links.go_back_up')"
            hreflang="{!! explode('-', app()->currentLocale())[0] !!}">@lang('links.go_back_up')</a>
    </div>
    <nav class="footer__nav">
        <h2 class="hidden">@lang('titles.footer')</h2>
        <div class="footer__nav__logo">
            <a class="card-link" title="@lang('links.home')" hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="/">Lokkalt</a>
            <img width="327" height="150" src="{{ asset('storage/svg/footer-logo.svg') }}"
                alt="{!! __('titles.footer_logo') !!}" title="{!! __('titles.footer_logo') !!}">
        </div>
        <ul class="footer__nav__links" aria-label="@lang('titles.main_links')">
            <li><a class="footer__nav__links__link link-white" title="{!! __('links.categories') !!}"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.categories') }}">@lang('links.categories')</a>
            </li>
            {{-- <li><a class="footer__nav__links__link link-white" title="@lang('links.around_me')" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="#">@lang('links.around_me')</a></li> --}}
            <li><a class="footer__nav__links__link link-white" title="@lang('links.articles')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.articles') }}">@lang('links.articles')</a>
            </li>
            <li><a class="footer__nav__links__link link-white" title="@lang('links.shops')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.shops') }}">@lang('links.shops')</a>
            </li>
            <li><a class="footer__nav__links__link link-white" title="@lang('links.sell_on_lokkalt')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.sell-on-lokkalt') }}">@lang('links.sell_on_lokkalt')</a>
            </li>
        </ul>
        <ul class="footer__nav__your-links" aria-label="{!! __('titles.you') !!}">
            <li><a class="footer__nav__your-links__link link-white" title="@lang('links.my_profile')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.my-profile') }}">@lang('links.my_profile')</a>
            </li>
            <li><a class="footer__nav__your-links__link link-white" title="@lang('links.my_cart')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.cart') }}">@lang('links.my_cart')</a>
            </li>
        </ul>
        <ul class="footer__nav__help" aria-label="@lang('titles.need_help')">
            <li><a class="footer__nav__help__links link-white" title="@lang('links.contact_form')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                    href="{{ LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.support') }}">@lang('links.contact_form')</a>
            </li>
        </ul>
    </nav>
    <div class="footer__mentions">
        <h3 class="hidden">@lang('links.legal_links')</h3>
        <ul class="footer__mentions__list" aria-label="@lang('links.mention')">
            <li><a class="footer__mentions__list__item link-white" title="@lang('links.general_conditions')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#">@lang('links.general_conditions')</a></li>
            <li><a class="footer__mentions__list__item link-white" title="@lang('links.your_personal_infos')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#">@lang('links.your_personal_infos')</a></li>
            <li><a class="footer__mentions__list__item link-white" title="@lang('links.legal_infos')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#">@lang('links.legal_infos')</a></li>
            <li><a class="footer__mentions__list__item link-white" title="@lang('links.cookies')"
                    hreflang="{!! explode('-', app()->currentLocale())[0] !!}" href="#">@lang('links.cookies')</a></li>
            <li>&copy; Lokkalt {!! date('Y') !!}</li>
        </ul>
    </div>
</footer>
