<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <a href="/" class="btn btn-secondary mb-3">&larr; Retour au catalogue</a>
    <h1>Inscription</h1>
    <form method="POST" action="{{ route('inscription') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-success">S'inscrire</button>
    </form>
    <p class="mt-3">Déjà un compte ? <a href="{{ route('connexion') }}">Se connecter</a></p>
</div>
</body>
</html> 