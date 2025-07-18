<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <a href="/" class="btn btn-secondary mb-3">&larr; Retour au catalogue</a>
    <div class="row">
        <div class="col-md-5">
            @if($product->image)
                <img src="{{ $product->image }}" class="img-fluid" alt="{{ $product->name }}">
            @else
                <img src="https://via.placeholder.com/400x400?text=Produit" class="img-fluid" alt="Produit">
            @endif
        </div>
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>
            <h4 class="text-success">{{ number_format($product->price, 0, ',', ' ') }} F CFA</h4>
            <p>{{ $product->description }}</p>
            <p><strong>Stock :</strong> {{ $product->stock }}</p>
            <form method="POST" action="{{ route('panier.ajouter') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantité</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                </div>
                <button type="submit" class="btn btn-primary">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>
</body>
</html> 