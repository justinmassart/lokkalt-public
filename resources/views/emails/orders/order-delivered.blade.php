<!DOCTYPE html>
<html>

<head>
    <title>Your order has been delivered/retreived</title>
</head>

<body>
    <div>
        <h1>Your order has been delivered/retreived.</h1>
        <p>Dear {!! "$user->firstname $user->lastname" !!},</p>
        <p>We wanted to inform you that your order with the reference {!! $order->reference !!} from
            {!! $order->shop->name !!} has been delivered or picked up.</p>
        <p>Here are the items that have been picked up :</p>
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

        <p>Best Regards,</p>
        <p>The Lokkalt Team</p>
    </div>
</body>

</html>
