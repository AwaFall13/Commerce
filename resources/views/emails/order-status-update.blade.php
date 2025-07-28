<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mise à jour de commande</title>
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
            background-color: #007bff;
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
        .status-update {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #dee2e6;
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
        <h1>📦 Mise à jour de votre commande</h1>
        <p>Bonjour {{ $customerName }},</p>
    </div>

    <div class="content">
        <h2>Statut de votre commande mis à jour</h2>
        
        <div class="status-update">
            <h3>Nouveau statut : {{ $newStatus }}</h3>
            <p>Votre commande <strong>{{ $orderNumber }}</strong> est maintenant <strong>{{ $newStatus }}</strong>.</p>
        </div>

        <div class="order-details">
            <h3>Détails de la commande</h3>
            <p><strong>Numéro de commande :</strong> {{ $orderNumber }}</p>
            <p><strong>Date de commande :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
            <p><strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</p>
            <p><strong>Adresse de livraison :</strong><br>
            {{ $order->shipping_address }}<br>
            {{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
        </div>

        @if($newStatus === 'Expédiée')
            <div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <h4>🚚 Votre commande a été expédiée !</h4>
                <p>Votre commande est en route vers vous. Vous devriez la recevoir dans les prochains jours.</p>
            </div>
        @endif

        @if($newStatus === 'Livrée')
            <div style="background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">
                <h4>✅ Votre commande a été livrée !</h4>
                <p>Votre commande a été livrée avec succès. Merci pour votre confiance !</p>
            </div>
        @endif

        <div style="text-align: center; margin: 20px 0;">
            <a href="{{ url('/order/history') }}" class="btn">Voir mes commandes</a>
            <a href="{{ url('/catalogue') }}" class="btn">Faire un nouvel achat</a>
        </div>
    </div>

    <div class="footer">
        <p>Pour toute question, contactez-nous à contact@ecommerce.com</p>
    </div>
</body>
</html> 