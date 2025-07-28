<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue produits</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .product-img {
            max-height: 120px;
            width: auto;
            display: block;
            margin: 0 auto 10px auto;
            object-fit: contain;
        }
    </style>
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <h1 class="mb-4">Catalogue de produits</h1>

    <!-- Formulaire de recherche et filtres -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('catalogue') }}" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Rechercher un produit..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('catalogue') }}" class="d-flex">
                <select name="category" class="form-select me-2">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary">Filtrer</button>
            </form>
        </div>
    </div>

    @if(request('search') || request('category'))
        <div class="alert alert-info">
            <strong>Filtres actifs :</strong>
            @if(request('search'))
                Recherche : "{{ request('search') }}"
            @endif
            @if(request('category'))
                @php $selectedCategory = $categories->find(request('category')); @endphp
                @if($selectedCategory)
                    Catégorie : {{ $selectedCategory->name }}
                @endif
            @endif
            <a href="{{ route('catalogue') }}" class="btn btn-sm btn-outline-secondary ms-2">Effacer les filtres</a>
        </div>
    @endif

    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ $product->image_url }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ number_format($product->price, 0, ',', ' ') }} F CFA</p>
                            <a href="{{ route('produit.detail', $product->id) }}" class="btn btn-primary">Voir</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    @else
        <p>Aucun produit trouvé pour vos critères de recherche.</p>
    @endif
</div>
</body>
</html>
