<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des commandes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📋 Historique de mes commandes</h2>
                <a href="{{ route('catalogue') }}" class="btn btn-outline-primary">
                    🛒 Continuer mes achats
                </a>
            </div>

            @if($orders->count() > 0)
                <div class="row">
                    @foreach($orders as $order)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Commande #{{ $order->order_number }}</h6>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'processing' ? 'info' : 'warning') }}">
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <small class="text-muted">Date : {{ $order->created_at->format('d/m/Y à H:i') }}</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Total :</strong> {{ number_format($order->total_amount, 0, ',', ' ') }} F CFA
                                    </div>

                                    <div class="mb-3">
                                        <strong>Produits :</strong>
                                        <ul class="list-unstyled mt-2">
                                            @foreach($order->orderItems->take(3) as $item)
                                                <li class="small">
                                                    {{ $item->product->name }} (x{{ $item->quantity }})
                                                </li>
                                            @endforeach
                                            @if($order->orderItems->count() > 3)
                                                <li class="small text-muted">... et {{ $order->orderItems->count() - 3 }} autres</li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Paiement :</strong>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $order->payment_status_label }}
                                        </span>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('order.details', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                            📋 Voir les détails
                                        </a>
                                        <a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-outline-secondary btn-sm">
                                            📄 Télécharger facture
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <h3>📦 Aucune commande trouvée</h3>
                        <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
                    </div>
                    <a href="{{ route('catalogue') }}" class="btn btn-primary btn-lg">
                        🛒 Découvrir nos produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html> 