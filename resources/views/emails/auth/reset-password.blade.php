<!DOCTYPE html>
<html>

<head>
    <title>Password Reset</title>
</head>

<body>
    <div>
        <h1>Password Reset</h1>
        <p>Dear {!! "$firstname $lastname" !!},</p>
        <p>You recenlty asked to reset your password on Lokkalt for the following account :</p>
        <p>{!! $email !!}</p>
        <p>Please click on the link below to reset your password : </p>
        <p><a href="{!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.reset_password_update', [
            'email' => $email,
            'token' => $token,
        ]) !!}">Reset my password</a></p>
        <p>If you have trouble with the above button you copy and paste this link in your browser :
            {!! LaravelLocalization::getURLFromRouteNameTranslated(app()->currentLocale(), 'routes.reset_password_update', [
                'email' => $email,
                'token' => $token,
            ]) !!}</p>
        <p>If you did not asked to reset your password, please ignore this email.</p>
        <p>Best Regards,</p>
        <p>The Lokkalt Team</p>
    </div>
</body>

</html>
