<!DOCTYPE html>
<html>

<head>
    <title>About your order {!! $order->reference !!}</title>
</head>

<body>
    <div>
        <h1>About your order {!! $order->reference !!}</h1>
        <p>Dear {!! "$user->firstname $user->lastname" !!},</p>
        <p>{!! $order->shop->name !!} has a message for you concerning your order {!! $order->reference !!} that you made the
            {!! date('d/m/Y', strtotime($order->created_at)) !!} for the following reason : {!! $reason !!}</p>
        <p>Here is the message :</p>
        <p>{!! $msg !!}</p>
        <p>If needed, here are the contact informations of {!! $order->shop->name !!} :</p>
        @if ($order->shop->email)
            <p>Email : <a href="mailto:{!! $order->shop->email !!}">{!! $order->shop->email !!}</a></p>
        @endif
        @if ($order->shop->phone)
            <p>Phone : <a href="tel:{!! $order->shop->phone !!}">{!! $order->shop->phone !!}</a></p>
        @endif
        @if ($order->shop->address)
            <p>Address : {!! $order->shop->address !!}</p>
        @endif
        <p>Here are the opening hours provided by {!! $order->shop->name !!} :</p>
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Opening Hours</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($openingHours as $day => $hours)
                    <tr>
                        <td>{{ ucfirst($day) }}</td>
                        <td>
                            @foreach ($hours as $hour)
                                @if ($hour['from'] && $hour['to'])
                                    From {{ $hour['from'] }} to {{ $hour['to'] }}<br>
                                @else
                                    Closed
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>The hours above may not be up to date, make sure to contact the shop if needed.</p>
        <p>Best Regards,</p>
        <p>The Lokkalt Team</p>
    </div>
</body>

</html>
