<!DOCTYPE html>
<html>

<head>
    <title>@lang('mails.welcome_to_lokkalt')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap"
        rel="stylesheet">
    <style>
        {!! file_get_contents(resource_path('css/reset.css')) !!}
        /*  */
        {!! file_get_contents(resource_path('css/mail.css')) !!}

        /*  */
        body {
            background-image: url({!! asset('storage/svg/background.svg') !!});
            background-repeat: no-repeat;
            background-position: top center;
            background-attachment: scroll;
            background-size: cover;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <img class="logo" src="{!! asset('storage/svg/logo.svg') !!}" alt="">
            </td>
        </tr>
        <tr>
            <td>
                <h1 class="title">@lang('mails.welcome_to_lokkalt')</h1>
            </td>
        </tr>
        <tr>
            <td>
                <div class="main-content">
                    <div class="intro">
                        <p class="intro__user">@lang('mails.hello') <strong>{!! "$firstname $lastname" !!}</strong>,</p>
                        <p class="intro__desc">@lang('mails.register_tanks')</p>
                    </div>
                    <div class="main">
                        <p class="main__action">@lang('mails.register_confirmation')</p>
                        <a class="main__button button" href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirmed-email', [
                            'email' => $email,
                            'token' => $token,
                        ]) !!}">@lang('mails.verify_mail')</a>
                        <p class="main__not-you">@lang('mails.register_not_you')</p>
                    </div>
                    <div class="end">
                        <p>@lang('mails.regards')<br>@lang('mails.lokkalt_team')</p>
                    </div>
                    <div class="trouble">
                        <p class="trouble__desc">@lang('mails.register_trouble')</p>
                        <a href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirmed-email', [
                            'email' => $email,
                            'token' => $token,
                        ]) !!}" class="trouble__link">{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.confirmed-email', [
                            'email' => $email,
                            'token' => $token,
                        ]) !!}</a>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
