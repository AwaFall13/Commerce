<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .product-list {
            margin: 15px 0;
        }
        .product-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Commande confirmée</h1>
        <p>Merci pour votre commande, {{ $customerName }} !</p>
    </div>

    <div class="content">
        <h2>Détails de votre commande</h2>
        
        <div class="order-details">
            <p><strong>Numéro de commande :</strong> {{ $orderNumber }}</p>
            <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
            <p><strong>Mode de paiement :</strong> {{ $paymentMethod }}</p>
            <p><strong>Total :</strong> {{ $totalAmount }}</p>
        </div>

        <h3>Adresse de livraison</h3>
        <div class="order-details">
            <p>{{ $order->shipping_address }}</p>
            <p>{{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
            <p>Téléphone: {{ $order->shipping_phone }}</p>
        </div>

        <h3>Produits commandés</h3>
        <div class="product-list">
            @foreach($order->orderItems as $item)
                <div class="product-item">
                    <strong>{{ $item->product->name }}</strong><br>
                    Quantité: {{ $item->quantity }} | 
                    Prix: {{ number_format($item->price, 0, ',', ' ') }} F CFA |
                    Total: {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F CFA
                </div>
            @endforeach
        </div>

        <div class="total">
            Total: {{ $totalAmount }}
        </div>

        @if($order->payment_method === 'cash_on_delivery')
            <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <strong>Paiement à la livraison :</strong> Vous paierez en espèces à la réception de votre commande.
            </div>
        @endif

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ url('/order/history') }}" class="btn">Voir mes commandes</a>
            <a href="{{ url('/catalogue') }}" class="btn">Continuer mes achats</a>
        </div>
    </div>

    <div class="footer">
        <p>Merci pour votre confiance !</p>
        <p>Pour toute question, contactez-nous à contact@ecommerce.com</p>
    </div>
</body>
</html> 