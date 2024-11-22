<!DOCTYPE html>
<html>

<head>
    <title>Account Deletion</title>
    <style>
        //
    </style>
</head>

<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center">
                            <img src="{!! asset('storage/svg/logo.svg') !!}" alt="Logo">
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <h1>Account Deletion</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>Dear {!! "$firstname $lastname" !!},</p>
                            <p>You recently asked to delete your account on Lokkalt.</p>
                            <p>Everything related to your account will be deleted and cannot be retrieved.</p>
                            <p>Please click on the link below to confirm your account deletion:</p>
                            <p><a href="{!! route('delete-account', ['email' => $email, 'token' => $token]) !!}">Delete my account</a></p>
                            <p>If you have trouble with the above button, you can copy and paste this link in your
                                browser:
                                {!! route('delete-account', ['email' => $email, 'token' => $token]) !!}
                            </p>
                            <p>If you did not ask to delete your account, please ignore this email.</p>
                            <p>Best Regards,</p>
                            <p>The Lokkalt Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
