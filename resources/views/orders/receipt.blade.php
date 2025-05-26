@php use App\Constants\OrderStatus; @endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title></title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 14px;
            line-height: 1.4;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header, .footer {
            text-align: center;
            margin-bottom: 30px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info h2 {
            margin: 0 0 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .right {
            text-align: right;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Recibo de Pedido</h1>
        <p>Pedido: <strong>{{ $order->uuid }}</strong></p>
        <p>Status: <strong>{{ OrderStatus::translate($order->status) }}</strong></p>
    </div>

    <div class="info">
        <h2>Endereço de Entrega</h2>
        <p>{{ $address->street }}
            , {{ $address->number }}{{ $address->complement ? ' - ' . $address->complement : '' }}</p>
        <p>{{ $address->neighborhood }} - {{ $address->city }}/{{ $address->state }}</p>
        <p>CEP: {{ $address->zip_code }}</p>
    </div>

    <table>
        <thead>
        <tr>
            <th>Qtd.</th>
            <th>Produto</th>
            <th>Valor Unitário</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->product_reference_uuid }}</td>
                <td class="right">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                <td class="right">R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table>
        <tr>
            <td class="total">Subtotal:</td>
            <td class="right">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
        </tr>
        @if($order->subtotal > $order->total)
            <tr>
                <td class="total">Descontos:</td>
                <td class="right">R$ {{ number_format($order->subtotal - $order->total, 2, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
            <td class="total">Total:</td>
            <td class="right">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Obrigado pela sua compra!</p>
    </div>
</div>
</body>
</html>
