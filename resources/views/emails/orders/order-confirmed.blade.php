<!DOCTYPE html>
<html>

<head>
    <title>Purchase Confirmed</title>
</head>

<body>
    <div>
        <h1>Purchase Confirmed</h1>
        <p>Dear {!! "$user->firstname $user->lastname" !!},</p>
        <p>We wanted to inform you that you recent purchase on Lokkalt has been confirmed.</p>
        <p>Here is a reminder of the content of your order :</p>
        <table>
            <thead>
                <tr>
                    <th>Article Name</th>
                    <th>Variant Name</th>
                    <th>Shop</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Sub-Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($orders as $order)
                    @php
                        $total += $order->sub_total;
                    @endphp
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{!! $item->shopArticle->article->name !!}</td>
                            <td>{!! $item->shopArticle->variant->name !!}</td>
                            <td>{!! $order->shop->name !!}</td>
                            <td>{!! $item->quantity !!}</td>
                            <td>{!! $item->price !!}€</td>
                            <td>{!! $order->sub_total !!}€</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        @php
            $total =
                $total +
                ($total * config('services.stripe.fee_percentage')) / 100 +
                config('services.stripe.fee_fixed');
            $total = round($total, 2);
        @endphp
        <p>For a total of : {!! $total !!}€</p>
        <p>You will receive updates of your order(s) by emails or by notifications on Lokkalt website.</p>
        <p>Best Regards,</p>
        <p>The Lokkalt Team</p>
    </div>
</body>

</html>
