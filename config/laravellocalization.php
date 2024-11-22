<?php

return [

    // Uncomment the languages that your site supports - or add new ones.
    // These are sorted by the native name, which is the order you might show them in a language selector.
    // Regional languages are sorted by their base language, so "British English" sorts as "English, British"
    'supportedLocales' => [
        'de-DE' => ['name' => 'German Germany',                 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_DE'],
        'de-FR' => ['name' => 'German France',                 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_FR'],
        'de-BE' => ['name' => 'German Belgium',                 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_BE'],
        'de-NL' => ['name' => 'German Netherlands',                 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_NL'],
        'de-LU' => ['name' => 'German Luxembourg',                 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_LU'],
        'de-GB' => ['name' => 'German England',                 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_GB'],
        //'en-GB' => ['name' => 'British English',        'script' => 'Latn', 'native' => 'British English', 'regional' => 'en_GB'],
        'en-FR' => ['name' => 'British France',        'script' => 'Latn', 'native' => 'British English', 'regional' => 'en_FR'],
        'en-BE' => ['name' => 'British Belgium',        'script' => 'Latn', 'native' => 'British English', 'regional' => 'en_BE'],
        'en-NL' => ['name' => 'British Netherlandss',        'script' => 'Latn', 'native' => 'British English', 'regional' => 'en_NL'],
        'en-LU' => ['name' => 'British Luxembourg',        'script' => 'Latn', 'native' => 'British English', 'regional' => 'en_LU'],
        'en-DE' => ['name' => 'British Germany',        'script' => 'Latn', 'native' => 'British English', 'regional' => 'en_DE'],
        'fr-FR' => ['name' => 'French France',                 'script' => 'Latn', 'native' => 'français', 'regional' => 'fr_FR'],
        'fr-BE' => ['name' => 'French Belgium',                 'script' => 'Latn', 'native' => 'français', 'regional' => 'fr_BE'],
        'fr-NL' => ['name' => 'French Netherlands',                 'script' => 'Latn', 'native' => 'français', 'regional' => 'fr_NL'],
        'fr-LU' => ['name' => 'French Luxembourg',                 'script' => 'Latn', 'native' => 'français', 'regional' => 'fr_LU'],
        'fr-DE' => ['name' => 'French Deutschland',                 'script' => 'Latn', 'native' => 'français', 'regional' => 'fr_DE'],
        'fr-GB' => ['name' => 'French England',                 'script' => 'Latn', 'native' => 'français', 'regional' => 'fr_GB'],
        'nl-NL' => ['name' => 'Dutch Netherlands',                  'script' => 'Latn', 'native' => 'Nederlands', 'regional' => 'nl_NL'],
        'nl-BE' => ['name' => 'Dutch Belgium',                  'script' => 'Latn', 'native' => 'Nederlands', 'regional' => 'nl_BE'],
        'nl-FR' => ['name' => 'Dutch France',                  'script' => 'Latn', 'native' => 'Nederlands', 'regional' => 'nl_FR'],
        'nl-DE' => ['name' => 'Dutch Germany',                  'script' => 'Latn', 'native' => 'Nederlands', 'regional' => 'nl_DE'],
        'nl-GB' => ['name' => 'Dutch England',                  'script' => 'Latn', 'native' => 'Nederlands', 'regional' => 'nl_GB'],
        'nl-LU' => ['name' => 'Dutch Luxembourg',                  'script' => 'Latn', 'native' => 'Nederlands', 'regional' => 'nl_LU'],
    ],

    // Requires middleware `LaravelSessionRedirect.php`.
    //
    // Automatically determine locale from browser (https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Accept-Language)
    // on first call if it's not defined in the URL. Redirect user to computed localized url.
    // For example, if users browser language is `de`, and `de` is active in the array `supportedLocales`,
    // the `/about` would be redirected to `/de/about`.
    //
    // The locale will be stored in session and only be computed from browser
    // again if the session expires.
    //
    // If false, system will take app.php locale attribute
    'useAcceptLanguageHeader' => true,

    // If `hideDefaultLocaleInURL` is true, then a url without locale
    // is identical with the same url with default locale.
    // For example, if `en` is default locale, then `/en/about` and `/about`
    // would be identical.
    //
    // If in addition the middleware `LaravelLocalizationRedirectFilter` is active, then
    // every url with default locale is redirected to url without locale.
    // For example, `/en/about` would be redirected to `/about`.
    // It is recommended to use `hideDefaultLocaleInURL` only in
    // combination with the middleware `LaravelLocalizationRedirectFilter`
    // to avoid duplicate content (SEO).
    //
    // If `useAcceptLanguageHeader` is true, then the first time
    // the locale will be determined from browser and redirect to that language.
    // After that, `hideDefaultLocaleInURL` behaves as usual.
    'hideDefaultLocaleInURL' => env('LARAVEL_LOCALIZATION_HIDE_LOCALE', true),

    // If you want to display the locales in particular order in the language selector you should write the order here.
    //CAUTION: Please consider using the appropriate locale code otherwise it will not work
    //Example: 'localesOrder' => ['es','en'],
    'localesOrder' => [],

    // If you want to use custom language URL segments like 'at' instead of 'de-AT', you can map them to allow the
    // LanguageNegotiator to assign the desired locales based on HTTP Accept Language Header. For example, if you want
    // to use 'at' instead of 'de-AT', you would map 'de-AT' to 'at' (ie. ['de-AT' => 'at']).
    'localesMapping' => [],

    // Locale suffix for LC_TIME and LC_MONETARY
    // Defaults to most common ".UTF-8". Set to blank on Windows systems, change to ".utf8" on CentOS and similar.
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    // URLs which should not be processed, e.g. '/nova', '/nova/*', '/nova-api/*' or specific application URLs
    // Defaults to []
    'urlsIgnored' => ['/skipped'],

    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
