<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Email</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #333;
        }
        h1 {
            font-size: 24px;
        }
        h2 {
            font-size: 20px;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f0f0f0;
            color: #555;
        }
        .total {
            font-weight: bold;
            background: #e9ecef;
        }
        address {
            font-style: normal;
            margin: 0;
            padding: 10px 0;
            border: 1px solid #ccc;
            background: #f9f9f9;
            border-radius: 4px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($mailData['userType'] == 'customer')
        <h1>Thanks for Your Order!</h1>
        <h2>Your Order ID is: #{{$mailData['order']->id}}</h2>
        @else
        <h1>You Have Received a New Order</h1>
        <h2>Order ID: #{{$mailData['order']->id}}</h2>
        @endif

        <h2>Shipping Address</h2>
        <address>
            <strong>{{$mailData['order']->first_name.' '.$mailData['order']->last_name}}</strong><br>
            {{$mailData['order']->address}}<br>
            {{$mailData['order']->city}}, {{$mailData['order']->zip}} {{getCountryInfo($mailData['order']->country_id)->name}}<br>
            Phone: {{$mailData['order']->mobile}}<br>
            Email: {{$mailData['order']->email}}
        </address>

        <h2>Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>                                        
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>${{number_format($item->price, 2)}}</td>                                        
                    <td>{{$item->qty}}</td>
                    <td>${{number_format($item->total, 2)}}</td>
                </tr>
                @endforeach

                <tr>
                    <th colspan="3" align="right">Subtotal:</th>
                    <td>${{number_format($mailData['order']->subtotal, 2)}}</td>
                </tr>
                
                <tr>
                    <th colspan="3" align="right">Shipping:</th>
                    <td>${{number_format($mailData['order']->shipping, 2)}}</td>
                </tr>
                <tr>
                    <th colspan="3" align="right">Discount: {{ !empty($mailData['order']->coupon_code) ? '('.$mailData['order']->coupon_code.')' : '' }}</th>
                    <td>${{number_format($mailData['order']->discount, 2)}}</td>
                </tr>
                <tr class="total">
                    <th colspan="3" align="right">Grand Total:</th>
                    <td>${{number_format($mailData['order']->grand_total, 2)}}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Thank you for shopping with us!</p>
        </div>
    </div>
</body>
</html>
