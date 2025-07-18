<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <h1>Dashboard Admin</h1>
    <p>Bienvenue, {{ $user->name }} (admin)</p>
    <div class="list-group mt-4">
        <a href="#" class="list-group-item list-group-item-action">Gestion des produits</a>
        <a href="#" class="list-group-item list-group-item-action">Gestion des catégories</a>
        <a href="#" class="list-group-item list-group-item-action">Gestion des commandes</a>
        <a href="#" class="list-group-item list-group-item-action">Gestion des utilisateurs</a>
        <a href="/api/admin/dashboard" class="list-group-item list-group-item-action">Statistiques (API)</a>
    </div>
</div>
</body>
</html> 