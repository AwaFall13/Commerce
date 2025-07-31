<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des produits - Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-box"></i> Gestion des produits</h1>
        <a href="{{ route('admin.produits.ajouter') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un produit
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Liste des produits</h5>
        </div>
        <div class="card-body">
            @if($produits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
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
                                <td>
                                    @if($prod->image)
                                        <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" 
                                             class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $prod->name }}</strong>
                                    @if($prod->description)
                                        <br><small class="text-muted">{{ Str::limit($prod->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($prod->category)
                                        <span class="badge bg-info">{{ $prod->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ number_format($prod->price, 0, ',', ' ') }} F CFA</strong>
                                </td>
                                <td>
                                    @if($prod->stock > 0)
                                        <span class="badge bg-success">{{ $prod->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">Rupture</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.produits.modifier', $prod->id) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.produits.supprimer', $prod->id) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $produits->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-box fa-3x text-muted mb-3"></i>
                    <h5>Aucun produit trouvé</h5>
                    <p class="text-muted">Commencez par ajouter votre premier produit.</p>
                    <a href="{{ route('admin.produits.ajouter') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un produit
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 