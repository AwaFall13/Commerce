<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            float: left;
            width: 50%;
        }
        .invoice-info {
            float: right;
            width: 45%;
            text-align: right;
        }
        .clear {
            clear: both;
        }
        .customer-info {
            margin-bottom: 30px;
        }
        .customer-info h3 {
            margin-bottom: 10px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        .payment-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>E-COMMERCE</h1>
        <p>Votre boutique en ligne de produits africains</p>
    </div>

    <div class="company-info">
        <h3>Informations de l'entreprise</h3>
        <p><strong>E-Commerce</strong><br>
        Adresse de l'entreprise<br>
        Téléphone: +33 921 28 28<br>
        Email: MYWA@e-commerce.com</p>
    </div>

    <div class="invoice-info">
        <h3>FACTURE</h3>
        <p><strong>Numéro :</strong> {{ $order->order_number }}</p>
        <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y') }}</p>
        <p><strong>Heure :</strong> {{ $order->created_at->format('H:i') }}</p>
    </div>

    <div class="clear"></div>

    <div class="customer-info">
        <h3>Informations du client</h3>
        <p><strong>Nom :</strong> {{ $order->user->name }}</p>
        <p><strong>Email :</strong> {{ $order->user->email }}</p>
        <p><strong>Adresse de livraison :</strong><br>
        {{ $order->shipping_address }}<br>
        {{ $order->shipping_city }} {{ $order->shipping_postal_code }}<br>
        Téléphone: {{ $order->shipping_phone }}</p>
    </div>

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
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;"><strong>Total TTC</strong></td>
                <td><strong>{{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="payment-info">
        <h4>Informations de paiement</h4>
        <p><strong>Mode de paiement :</strong> {{ $order->payment_method_label }}</p>
        <p><strong>Statut du paiement :</strong> {{ $order->payment_status_label }}</p>
        @if($order->payment_method === 'cash_on_delivery')
            <p><em>Paiement à effectuer à la livraison en espèces</em></p>
        @endif
    </div>

    @if($order->notes)
        <div style="margin-top: 20px;">
            <h4>Notes</h4>
            <p>{{ $order->notes }}</p>
        </div>
    @endif

    <div class="footer">
        <p><strong>Conditions de vente :</strong></p>
        <ul>
            <li>Les produits sont garantis conformes à la description</li>
            <li>Livraison sous 3-5 jours ouvrés</li>
            <li>Retour accepté sous 14 jours</li>
            <li>Pour toute question, contactez-nous</li>
        </ul>
        <p style="text-align: center; margin-top: 20px;">
            <strong>Merci pour votre confiance !</strong>
        </p>
    </div>
</body>
</html> 