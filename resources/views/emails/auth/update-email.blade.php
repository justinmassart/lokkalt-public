<!DOCTYPE html>
<html>

<head>
    <title>Email Update</title>
</head>

<body>
    <div>
        <h1>Email Update</h1>
        <p>Dear {!! "$firstname $lastname" !!},</p>
        <p>You recenlty asked to update your email on Lokkalt for the following account :</p>
        <p>{!! $email !!}</p>
        <p>Please copy the token below and paste it in the corresponding input in the profile form : </p>
        <p><strong>{!! $token !!}</strong></p>
        <p>If you did not asked to update your email to this email address, please ignore this email.</p>
        <p>Best Regards,</p>
        <p>The Lokkalt Team</p>
    </div>
</body>

</html>
