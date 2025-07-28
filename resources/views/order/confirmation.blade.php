<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">✅ Commande confirmée</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h5>Merci pour votre commande !</h5>
                        <p class="text-muted">Votre commande a été enregistrée avec succès.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations de la commande</h6>
                            <p><strong>Numéro de commande :</strong> {{ $order->order_number }}</p>
                            <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            <p><strong>Statut :</strong> <span class="badge bg-warning">{{ $order->status_label }}</span></p>
                            <p><strong>Mode de paiement :</strong> {{ $order->payment_method_label }}</p>
                            <p><strong>Statut du paiement :</strong> <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ $order->payment_status_label }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Adresse de livraison</h6>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                            <p>Tél: {{ $order->shipping_phone }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6>Produits commandés</h6>
                    <div class="table-responsive">
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
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th>{{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="mt-3">
                            <h6>Notes</h6>
                            <p class="text-muted">{{ $order->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-primary">
                            📄 Télécharger la facture
                        </a>
                        <a href="{{ route('order.history') }}" class="btn btn-secondary">
                            📋 Voir mes commandes
                        </a>
                        <a href="{{ route('catalogue') }}" class="btn btn-outline-primary">
                            🛒 Continuer mes achats
                        </a>
                    </div>

                    @if($order->payment_method === 'cash_on_delivery')
                        <div class="alert alert-info mt-3">
                            <strong>Paiement à la livraison :</strong> Vous paierez en espèces à la réception de votre commande.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 