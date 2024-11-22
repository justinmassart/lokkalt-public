<!DOCTYPE html>
<html>

<head>
    <title>Your order has been refunded</title>
</head>

<body>
    <div>
        <h1>Your order has been refunded</h1>
        <p>Dear {!! "$user->firstname $user->lastname" !!},</p>
        <p>We wanted to inform you that your order with the reference {!! $order->reference !!} from
            {!! $order->shop->name !!} has been refunded.</p>
        <p>{!! $order->shop->name !!} gave this reason : {!! $reason !!}</p>
        <p>Here are the articles that have been refunded :</p>
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
                @php
                    $total = 0;
                @endphp
                @foreach ($items as $item)
                    @php
                        $total += $item->price * $item->quantity;
                    @endphp
                    <tr>
                        <td>{!! $item->shopArticle->article->name !!}</td>
                        <td>{!! $item->shopArticle->variant->name !!}</td>
                        <td>{!! $item->quantity !!}</td>
                        <td>{!! $item->price !!}€</td>
                        <td>{!! $item->price * $item->quantity !!}€</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php
            $refunded =
                $total +
                ($total * config('services.stripe.fee_percentage')) / 100 +
                config('services.stripe.fee_fixed');
            $refunded = round($refunded, 2);
        @endphp
        <p>For a total of : {!! $refunded !!}€</p>
        <p>Best Regards,</p>
        <p>The Lokkalt Team</p>
    </div>
</body>

</html>
