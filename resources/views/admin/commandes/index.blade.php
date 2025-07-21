<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Commandes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <h1>Gestion des commandes</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Paiement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commandes as $commande)
            <tr>
                <td>{{ $commande->id }}</td>
                <td>{{ $commande->user->name ?? '-' }}</td>
                <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                <td>{{ number_format($commande->total, 0, ',', ' ') }} F CFA</td>
                <td>{{ ucfirst($commande->status) }}</td>
                <td>{{ $commande->is_paid ? 'Payée' : 'Non payée' }}</td>
                <td>
                    <a href="/api/orders/{{ $commande->id }}/invoice" class="btn btn-sm btn-outline-primary" target="_blank">Facture PDF</a>
                    @if(!$commande->is_paid)
                    <form method="POST" action="/admin/commandes/{{ $commande->id }}/pay" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Marquer comme payée</button>
                    </form>
                    @else
                    <span class="text-success">Déjà payée</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $commandes->links() }}
    </div>
</div>
</body>
</html> 