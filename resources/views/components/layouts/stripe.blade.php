<!DOCTYPE html>
<html lang="en">

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
    <meta property="og:image" content="{{ asset('storage/svg/favicon.svg') }}">
    <meta property="og:url" content="https://lokkalt.com">
    <meta property="og:type" content="website">
    <link rel="icon" href="{{ asset('storage/svg/favicon.svg') }}">
    <script defer src="https://js.stripe.com/v3/"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle', '') | Lokkalt</title>
    @vite(['resources/css/app.css'])
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
            {{ $slot }}
        </div>
    </main>
    <x-footer />
    @vite(['resources/js/app.js'])
    @livewireScripts
</body>

</html>
