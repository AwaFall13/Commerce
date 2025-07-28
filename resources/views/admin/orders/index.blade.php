<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des commandes - Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des commandes</h1>
        <a href="{{ route('admin.produits') }}" class="btn btn-secondary">Retour aux produits</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))) }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $order->payment_status_label }}
                                </span>
                                <br>
                                <small>{{ $order->payment_method_label }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                        Voir détails
                                    </button>
                                    <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-bs-toggle="dropdown">
                                        Modifier statut
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.order.status', ['id' => $order->id, 'status' => 'pending']) }}">En attente</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.order.status', ['id' => $order->id, 'status' => 'processing']) }}">En cours</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.order.status', ['id' => $order->id, 'status' => 'shipped']) }}">Expédiée</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.order.status', ['id' => $order->id, 'status' => 'delivered']) }}">Livrée</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.order.status', ['id' => $order->id, 'status' => 'cancelled']) }}">Annulée</a></li>
                                    </ul>
                                    @if($order->payment_status !== 'paid')
                                        <a href="{{ route('admin.order.pay', $order->id) }}" class="btn btn-sm btn-success">Marquer payé</a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal pour les détails de la commande -->
                        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Détails de la commande {{ $order->order_number }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Informations client</h6>
                                                <p><strong>Nom :</strong> {{ $order->user->name }}</p>
                                                <p><strong>Email :</strong> {{ $order->user->email }}</p>
                                                <p><strong>Adresse :</strong><br>
                                                {{ $order->shipping_address }}<br>
                                                {{ $order->shipping_city }} {{ $order->shipping_postal_code }}<br>
                                                Tél: {{ $order->shipping_phone }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Informations commande</h6>
                                                <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                                <p><strong>Statut :</strong> {{ $order->status_label }}</p>
                                                <p><strong>Paiement :</strong> {{ $order->payment_method_label }}</p>
                                                <p><strong>Statut paiement :</strong> {{ $order->payment_status_label }}</p>
                                            </div>
                                        </div>

                                        <hr>

                                        <h6>Produits commandés</h6>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Produit</th>
                                                    <th>Quantité</th>
                                                    <th>Prix</th>
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

                                        @if($order->notes)
                                            <div class="mt-3">
                                                <h6>Notes</h6>
                                                <p class="text-muted">{{ $order->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-primary">Télécharger facture</a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Aucune commande pour le moment.
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 