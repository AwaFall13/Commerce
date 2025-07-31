<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier utilisateur - Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier l'utilisateur</h1>
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Retour aux détails</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Informations de l'utilisateur</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nom *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1" 
                               {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin">
                            Administrateur
                        </label>
                    </div>
                    <small class="form-text text-muted">
                        Cochez cette case pour donner les droits d'administrateur à cet utilisateur.
                    </small>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Informations complémentaires</h5>
        </div>
        <div class="card-body">
            <p><strong>Date d'inscription :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</p>
            <p><strong>Dernière modification :</strong> {{ $user->updated_at->format('d/m/Y à H:i') }}</p>
            <p><strong>Nombre de commandes :</strong> {{ $user->orders->count() }}</p>
        </div>
    </div>
</div>
</body>
</html> 