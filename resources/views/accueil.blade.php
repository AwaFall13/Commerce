<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Boutique en ligne</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .hero {
            background: linear-gradient(120deg, #4A90E2 60%, #7FB3D3 100%);
            border-radius: 18px;
            padding: 2.5rem 2rem 2rem 2rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .hero h1 { color: #fff; font-weight: bold; }
        .hero p { font-size: 1.3rem; color: #fff; }
        .categories .card { border-radius: 16px; }
        .vedette .card { border-radius: 16px; border: 2px solid #4A90E2; }
        .vedette .card-title { color: #4A90E2; font-weight: bold; }
        .vedette .btn { background: #4A90E2; color: #fff; border-radius: 20px; }
        .vedette .btn:hover { background: #7FB3D3; color: #fff; }
        footer { background: #4A90E2; color: #fff; text-align: center; padding: 1.2rem 0 0.5rem 0; margin-top: 3rem; border-top-left-radius: 30px; border-top-right-radius: 30px; font-size: 1.1rem; }
    </style>
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <div class="hero shadow-sm">
        <h1>Bienvenue sur MYWA Boutique</h1>
        <p>Découvrez les meilleurs produits, promos et nouveautés du shopping en ligne au Sénégal.<br>Livraison rapide, paiement sécurisé, service client local.</p>
        <a href="/catalogue" class="btn btn-lg btn-primary mt-3">Voir tout le catalogue</a>
    </div>
    <h2 class="mb-3">Produits en vedette</h2>
    <div class="row vedette mb-4">
        @foreach($produits as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    @if($product->image)
                        <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/200x200?text=Produit" class="card-img-top" alt="Produit">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ number_format($product->price, 0, ',', ' ') }} F CFA</p>
                        <a href="/produits/{{ $product->id }}" class="btn btn-sm">Voir le produit</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <h2 class="mb-3">Nos catégories</h2>
    <div class="row categories mb-4">
        @foreach($categories as $cat)
            <div class="col-md-3 mb-2">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $cat->name }}</h5>
                        <a href="/catalogue?category_id={{ $cat->id }}" class="btn btn-outline-primary btn-sm">Voir</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html> 