<!DOCTYPE html>
<html>

<head>
    <title>Franchise Registration</title>
</head>

<body>
    <div>
        <h1>You asked to register a franchise on Lokkalt.</h1>
        <p>Dear {!! "$user->firstname $user->lastname" !!},</p>
        <p>You recently asked to register a franchise on Lokkalt. We are very grateful to know you want to become a
            member of
            our app.</p>
        <p>Here are the informations about the request that has been made.</p>
        <div>
            <p>About the person who made the request :</p>
            <p>Name: {!! $user->full_name !!}</p>
            <p>Email: {!! $user->email !!}</p>
            @if ($user->phone)
                <p>Phone number: {!! $user->phone !!}</p>
            @endif
            <p>Address: {!! $user->address !!}</p>
            <p>Country: {!! $user->country !!}</p>
        </div>
        <div>
            <p>About the shop to be registered :</p>
            <p>Name: {!! $franchise->name !!}</p>
            <p>Email: {!! $franchise->email !!}</p>
            @if ($franchise->phone)
                <p>Phone number: {!! $franchise->phone !!}</p>
            @endif
            <p>Address: {!! $franchise->address !!}</p>
            <p>Country: {!! $franchise->country !!}</p>
            <p>VAT: {!! $franchise->VAT !!}</p>
        </div>
        <p>To confirm the franchise registration and be able to create your first shop, please click on the following
            button :</p>
        <a href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.franchise-registration', [
            'email' => $franchise->email,
            'token' => $registrationToken->token,
        ]) !!}">Confirm Franchise Registration</a>
        <p>If you have difficulties with the above button, copy and paste the following link into your browser : </p>
        <a href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.franchise-registration', [
            'email' => $franchise->email,
            'token' => $registrationToken->token,
        ]) !!}">{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.franchise-registration', [
            'email' => $franchise->email,
            'token' => $registrationToken->token,
        ]) !!}</a>
        <p>If you didn’t asked to register a franchise on Lokkalt or if you do not recognize those informations, please
            ignore this email.</p>
        <p>Best Regards,</p>
        <p>The Lokkalt Team</p>
    </div>
</body>

</html>
