<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Facture n°{{ $order->id }}</h2>
    <p>Date : {{ $order->created_at->format('d/m/Y') }}</p>
    <p>Client : {{ $order->user->name }} ({{ $order->user->email }})</p>
    <p>Adresse : {{ $order->address }}</p>
    <p>Mode de paiement : {{ $order->payment_method }}</p>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', ' ') }} F CFA</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F CFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3 style="text-align:right;">Total TTC : {{ number_format($order->total, 0, ',', ' ') }} F CFA</h3>
</body>
</html> 