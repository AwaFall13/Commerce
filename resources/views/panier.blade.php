<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon panier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <a href="/" class="btn btn-secondary mb-3">&larr; Retour au catalogue</a>
    <h1>Mon panier</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(count($panier) === 0)
        <p>Votre panier est vide.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Image</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($panier as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td><img src="{{ $item['image'] }}" width="60"></td>
                    <td>{{ number_format($item['price'], 0, ',', ' ') }} F CFA</td>
                    <td>
                        <form method="POST" action="{{ route('panier.modifier') }}" class="d-flex align-items-center">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm me-2" style="width:70px;">
                            <button type="submit" class="btn btn-sm btn-outline-primary">OK</button>
                        </form>
                    </td>
                    <td>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} F CFA</td>
                    <td>
                        <form method="POST" action="{{ route('panier.supprimer') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['id'] }}">
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h4 class="text-end">Total : {{ number_format($total, 0, ',', ' ') }} F CFA</h4>
        <div class="text-end">
            <a href="{{ route('checkout') }}" class="btn btn-success btn-lg">Finaliser la commande</a>
        </div>
    @endif
</div>
</body>
</html> 