<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📋 Détails de la commande #{{ $order->order_number }}</h2>
                <a href="{{ route('order.history') }}" class="btn btn-outline-secondary">
                    ← Retour à l'historique
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-0">Informations de la commande</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'processing' ? 'info' : 'warning') }} fs-6">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations générales</h6>
                            <p><strong>Numéro de commande :</strong> {{ $order->order_number }}</p>
                            <p><strong>Date de commande :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                            <p><strong>Statut :</strong> {{ $order->status_label }}</p>
                            <p><strong>Mode de paiement :</strong> {{ $order->payment_method_label }}</p>
                            <p><strong>Statut du paiement :</strong> 
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $order->payment_status_label }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Adresse de livraison</h6>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                            <p><strong>Téléphone :</strong> {{ $order->shipping_phone }}</p>
                        </div>
                    </div>

                    @if($order->notes)
                        <hr>
                        <div>
                            <h6>Notes</h6>
                            <p class="text-muted">{{ $order->notes }}</p>
                        </div>
                    @endif

                    <hr>

                    <h6>Produits commandés</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->image)
                                                    <img src="{{ asset('images/' . $item->product->image) }}" 
                                                         alt="{{ $item->product->name }}" 
                                                         class="me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    @if($item->product->description)
                                                        <br><small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', ' ') }} F CFA</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F CFA</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th class="text-end">{{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-primary">
                            📄 Télécharger la facture
                        </a>
                        <a href="{{ route('order.history') }}" class="btn btn-outline-secondary">
                            📋 Retour à l'historique
                        </a>
                        <a href="{{ route('catalogue') }}" class="btn btn-outline-primary">
                            🛒 Continuer mes achats
                        </a>
                    </div>

                    @if($order->payment_method === 'cash_on_delivery')
                        <div class="alert alert-info mt-3">
                            <strong>💡 Paiement à la livraison :</strong> Vous paierez en espèces à la réception de votre commande.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 