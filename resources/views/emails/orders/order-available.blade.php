<!DOCTYPE html>
<html>

<head>
    <title>Your order is available</title>
</head>

<body>
    <div>
        <h1>Your order is available</h1>
        <p>Dear {!! "$user->firstname $user->lastname" !!},</p>
        <p>We wanted to inform you that your order with the reference {!! $order->reference !!} from
            {!! $order->shop->name !!} is available to pickup.</p>
        <p>Here is a reminder of the content of your order :</p>
        <table>
            <thead>
                <tr>
                    <th>Article Name</th>
                    <th>Variant Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Sub-Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{!! $item->shopArticle->article->name !!}</td>
                        <td>{!! $item->shopArticle->variant->name !!}</td>
                        <td>{!! $item->quantity !!}</td>
                        <td>{!! $item->price !!}€</td>
                        <td>{!! $order->sub_total !!}€</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>For a total of : {!! $order->total !!}</p>
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
