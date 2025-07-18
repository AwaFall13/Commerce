<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Produits</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <h1>Gestion des produits</h1>
    <a href="#" class="btn btn-success mb-3">Ajouter un produit</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $prod)
            <tr>
                <td>{{ $prod->id }}</td>
                <td>{{ $prod->name }}</td>
                <td>{{ $prod->category->name ?? '-' }}</td>
                <td>{{ number_format($prod->price, 0, ',', ' ') }} F CFA</td>
                <td>{{ $prod->stock }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary">Modifier</a>
                    <a href="#" class="btn btn-sm btn-danger">Supprimer</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $produits->links() }}
    </div>
</div>
</body>
</html> 