<!DOCTYPE html>
<html lang="{!! explode('-', app()->currentLocale())[0] !!}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
        content="Dynamiser votre commerce, ou acheter de meilleurs produits près de chez vous avec Lokkalt. Lokkalt est autant un outil de gestion pour gagner du temps et de l’argent dans votre commerce en simplifiant certaines quotidiennes... Que pour faire vos courses près de chez vous dans les commerces qui en ont bien besoin !">
    <meta name="keywords"
        content="commerce, local, commerce local, articles, produits, courses, gestion, e-shop, e-commerce, commerce en ligne">
    <meta name="author" content="Justin Massart, justin@lokkalt.com">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Lokkalt">
    <meta property="og:description"
        content="Dynamiser votre commerce, ou acheter de meilleurs produits près de chez vous avec Lokkalt. Lokkalt est autant un outil de gestion pour gagner du temps et de l’argent dans votre commerce en simplifiant certaines quotidiennes... Que pour faire vos courses près de chez vous dans les commerces qui en ont bien besoin !">
    <meta property="og:image" content="{{ asset('storage/img/share.jpg') }}">
    <meta property="og:url" content="https://lokkalt.com">
    <meta property="og:type" content="website">
    <link rel="icon" href="{{ asset('storage/svg/favicon.svg') }}">
    @if (Route::currentRouteName() === 'home')
        <title>Lokkalt</title>
    @else
        <title>@yield('pageTitle', '') | Lokkalt</title>
    @endif
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="body" id="top">
    <div class="body__background"></div>
    @auth
        <x-auth-header />
    @endauth
    @guest
        <x-header />
    @endguest
    <main class="main">
        <div class="main__container">
            @if (Route::currentRouteName() !== 'home')
                <div class="breadcrumb">
                    <ul class="breadcrumb__list">
                        <li class="breadcrumb__list__item"><a class="link" href="/">Lokkalt</a></li>
                        @php
                            $segments = array_slice(request()->segments(), 1);
                            $url = '';
                        @endphp
                        @foreach ($segments as $segment)
                            @php
                                $url .= '/' . $segment;
                            @endphp
                            <li class="breadcrumb__list__item">
                                <a class="link" hreflang="{!! explode('-', app()->currentLocale())[0] !!}"
                                    href="{!! url('/') . '/' . app()->currentLocale() . $url !!}">{{ strtolower($segment) }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{ $slot }}
        </div>
    </main>
    <x-footer />
    <livewire:notifications.popup />
    @livewireScripts
    @vite('resources/js/app.js')
</body>

</html>
