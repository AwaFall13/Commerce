<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue produits</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <h1 class="mb-4">Catalogue produits</h1>
    <div class="row">
        @foreach($products as $product)
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
                        <a href="#" class="btn btn-primary">Voir</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
</body>
</html> 