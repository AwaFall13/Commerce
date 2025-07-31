<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails utilisateur - Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détails de l'utilisateur</h1>
        <div>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Retour à la liste</a>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Modifier</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ $user->name }}</p>
                    <p><strong>Email :</strong> {{ $user->email }}</p>
                    <p><strong>Rôle :</strong> 
                        @if($user->is_admin)
                            <span class="badge bg-primary">Administrateur</span>
                        @else
                            <span class="badge bg-secondary">Client</span>
                        @endif
                    </p>
                    <p><strong>Date d'inscription :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Dernière connexion :</strong> {{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Statistiques</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre de commandes :</strong> {{ $user->orders->count() }}</p>
                    <p><strong>Total des achats :</strong> {{ number_format($user->orders->sum('total_amount'), 0, ',', ' ') }} F CFA</p>
                    <p><strong>Dernière commande :</strong> 
                        @if($user->orders->count() > 0)
                            {{ $user->orders->sortByDesc('created_at')->first()->created_at->format('d/m/Y') }}
                        @else
                            Aucune commande
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($user->orders->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5>Historique des commandes</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->sortByDesc('created_at') as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', ' ') }} F CFA</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $order->payment_status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('order.details', $order->id) }}" class="btn btn-sm btn-info">Voir</a>
                                        <a href="{{ route('order.invoice.download', $order->id) }}" class="btn btn-sm btn-outline-primary">Facture</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-4">
            Cet utilisateur n'a pas encore passé de commande.
        </div>
    @endif
</div>
</body>
</html> 