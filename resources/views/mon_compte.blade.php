<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <h1>Mon compte</h1>
    @if($user)
        <h4>Modifier mon profil</h4>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('mon-compte.modifier') }}" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
        <h4>Profil</h4>
        <ul>
            <li><strong>Nom :</strong> {{ $user->name }}</li>
            <li><strong>Email :</strong> {{ $user->email }}</li>
        </ul>
        <h4 class="mt-4">Historique des commandes</h4>
        @if(count($commandes) === 0)
            <p>Aucune commande passée.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Facture</th>
                        <th>Détail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandes as $commande)
                    <tr>
                        <td>{{ $commande->id }}</td>
                        <td>{{ $commande->created_at->format('d/m/Y') }}</td>
                        <td>{{ number_format($commande->total, 0, ',', ' ') }} F CFA</td>
                        <td>{{ ucfirst($commande->status) }}</td>
                        <td>{{ $commande->is_paid ? 'Payée' : 'Non payée' }}</td>
                        <td>
                            <a href="/api/orders/{{ $commande->id }}/invoice" class="btn btn-sm btn-outline-primary">Télécharger</a>
                        </td>
                        <td>
                            <a href="{{ route('mon-compte.commande', $commande->id) }}" class="btn btn-sm btn-info">Voir détail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <p>Vous devez être connecté pour accéder à votre compte.</p>
    @endif
</div>
</body>
</html> 