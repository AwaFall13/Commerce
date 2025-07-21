<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail commande #{{ $commande->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <a href="/mon-compte" class="btn btn-secondary mb-3">&larr; Retour à mon compte</a>
    <h1>Détail de la commande #{{ $commande->id }}</h1>
    <ul>
        <li><strong>Date :</strong> {{ $commande->created_at->format('d/m/Y') }}</li>
        <li><strong>Statut :</strong> {{ ucfirst($commande->status) }}</li>
        <li><strong>Statut de paiement :</strong> {{ $commande->is_paid ? 'Payée' : 'Non payée' }}</li>
        <li><strong>Total :</strong> {{ number_format($commande->total, 0, ',', ' ') }} F CFA</li>
        <li><strong>Adresse :</strong> {{ $commande->address }}</li>
        <li><strong>Mode de paiement :</strong> {{ $commande->payment_method }}</li>
        <li><a href="/api/orders/{{ $commande->id }}/invoice" class="btn btn-sm btn-outline-primary">Télécharger la facture</a></li>
    </ul>
    <h4>Produits commandés</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->orderItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', ' ') }} F CFA</td>
                <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F CFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html> 