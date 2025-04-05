<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Order Status Update</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="max-width: 700px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Hello {{ $customer->name }},</h2>

        <p>We wanted to let you know that the status of your order has been updated.</p>

        <h3 style="margin-top: 30px;">Order Details</h3>
        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Date Placed:</strong> {{ $order->date_placed }}</p>
        <p><strong>Status:</strong> <span style="color: #2e7d32; font-weight: bold;">{{ ucfirst($orderStatus) }}</span></p>

        @if(strtolower($orderStatus) === 'shipped')
            <p><strong>Date Shipped:</strong> {{ $order->date_shipped }}</p>
        @elseif(strtolower($orderStatus) === 'delivered')
            <p><strong>Date Shipped:</strong> {{ $order->date_shipped }}</p>
            <p><strong>Date Delivered:</strong> {{ $order->date_delivered }}</p>
        @endif

        <h3 style="margin-top: 30px;">Shipping Details</h3>
        @if(count($items) > 0)
        <p><strong>Shipping Region:</strong> {{ $items[0]->shipping_region }}</p>
        <p><strong>Shipping Rate:</strong> ₱{{ number_format($items[0]->shipping_rate, 2) }}</p>
        @endif

        <h3 style="margin-top: 30px;">Ordered Items</h3>
<table width="100%" cellpadding="10" cellspacing="0" style="border-collapse: collapse;">
    <thead style="background-color: #f3f3f3;">
        <tr>
            <th align="left">Item</th>
            <th align="center">Quantity</th>
            <th align="right">Price</th>
            <th align="right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @php
            $subtotal = 0;
            $uniqueItems = [];
            
            foreach ($items as $index => $item) {
                $itemKey = $item->item_id ?? $item->id; 
                
                if (!isset($uniqueItems[$itemKey])) {
                    $uniqueItems[$itemKey] = [
                        'name' => $item->item_name,
                        'price' => $item->sell_price,
                        'quantity' => (int)($item->quantity) 
                    ];
                }
            }
        @endphp
        
        @foreach ($uniqueItems as $item)
            @php
                $itemSubtotal = $item['quantity'] * $item['price'];
                $subtotal += $itemSubtotal;
            @endphp
            <tr style="border-bottom: 1px solid #eaeaea;">
                <td>{{ $item['name'] }}</td>
                <td align="center">{{ $item['quantity'] }}</td>
                <td align="right">₱{{ number_format($item['price'], 2) }}</td>
                <td align="right">₱{{ number_format($itemSubtotal, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div style="margin-top: 20px; text-align: right;">
    <p><strong>Subtotal:</strong> ₱{{ number_format($subtotal, 2) }}</p>
    @if(count($items) > 0)
    <p><strong>Shipping:</strong> ₱{{ number_format($items[0]->shipping_rate, 2) }}</p>
    @endif
    <h3 style="color: #2e7d32;">
        <strong>Grand Total: ₱{{ number_format($subtotal + $items[0]->shipping_rate, 2) }}</strong>
    </h3>
</div>

        <h3 style="margin-top: 30px; color: #2e7d32;">Thank you for shopping with us!</h3>
        <p>If you have any questions or need further assistance, feel free to contact us at any time.</p>

        <p style="margin-top: 40px;">Warm regards,</p>
        <p><strong>Plush-IT Team</strong></p>
    </div>
</body>

</html>