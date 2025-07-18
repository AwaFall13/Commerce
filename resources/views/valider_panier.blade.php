<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Valider la commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <a href="/panier" class="btn btn-secondary mb-3">&larr; Retour au panier</a>
    <h1>Valider la commande</h1>
    <form method="POST" action="{{ route('panier.valider.post') }}">
        @csrf
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse de livraison</label>
            <input type="text" class="form-control" id="adresse" name="adresse" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mode de paiement</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="paiement" id="paiement1" value="en ligne" checked>
                <label class="form-check-label" for="paiement1">Paiement en ligne (simulé)</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="paiement" id="paiement2" value="à la livraison">
                <label class="form-check-label" for="paiement2">Paiement à la livraison</label>
            </div>
        </div>
        <h4>Total à payer : {{ number_format($total, 0, ',', ' ') }} F CFA</h4>
        <button type="submit" class="btn btn-success">Valider la commande</button>
    </form>
</div>
</body>
</html> 