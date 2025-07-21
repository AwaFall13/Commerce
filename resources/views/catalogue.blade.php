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
                    @php
                        $images = [
                            'Bissap' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80',
                            'Pagnes tissés' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=400&q=80',
                            'Savon noir africain' => 'https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=400&q=80',
                            'Thiouraye' => 'https://www.afrikrea.com/media/produit/thiouraye-encens-senegalais-1-1.jpg',
                            'Sandales artisanales' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
                            'Baobab en poudre' => 'https://www.naturaforce.com/wp-content/uploads/2018/10/baobab-poudre.jpg',
                            'Boucles d’oreilles wax' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80',
                            'Panier en osier' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
                        ];
                        $img = (!empty($product->image) && filter_var($product->image, FILTER_VALIDATE_URL)) ? $product->image : ($images[$product->name] ?? 'https://via.placeholder.com/200x200?text=Produit');
                    @endphp
                    <img src="{{ $img }}" class="card-img-top" alt="{{ $product->name }}">
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