<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Valider la commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .payment-method-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method-card:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }
        .payment-method-card.selected {
            border-color: #007bff;
            background-color: #e3f2fd;
        }
        .payment-icon {
            font-size: 2rem;
            margin-right: 15px;
        }
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
        }
    </style>
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <a href="/panier" class="btn btn-secondary mb-3">&larr; Retour au panier</a>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-shipping-fast"></i> Informations de livraison</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('panier.valider.post') }}" id="orderForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adresse" class="form-label">Adresse de livraison *</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone *</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes de livraison (optionnel)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Instructions spéciales pour la livraison..."></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3><i class="fas fa-credit-card"></i> Mode de paiement</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Paiement avant livraison</h5>
                            <div class="payment-method-card" onclick="selectPayment('orange')">
                                <input type="radio" name="paiement" id="orange" value="orange" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-mobile-alt payment-icon text-warning"></i>
                                    <div>
                                        <strong>Orange Money</strong><br>
                                        <small>Paiement mobile sécurisé</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="payment-method-card" onclick="selectPayment('wave')">
                                <input type="radio" name="paiement" id="wave" value="wave" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-wave-square payment-icon text-primary"></i>
                                    <div>
                                        <strong>Wave</strong><br>
                                        <small>Paiement mobile rapide</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="payment-method-card" onclick="selectPayment('visa')">
                                <input type="radio" name="paiement" id="visa" value="visa" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-credit-card payment-icon text-info"></i>
                                    <div>
                                        <strong>Carte Visa/Mastercard</strong><br>
                                        <small>Paiement par carte bancaire</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Paiement après livraison</h5>
                            <div class="payment-method-card" onclick="selectPayment('livraison')">
                                <input type="radio" name="paiement" id="livraison" value="à la livraison" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave payment-icon text-success"></i>
                                    <div>
                                        <strong>Paiement à la livraison</strong><br>
                                        <small>Espèces à la réception</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="summary-card">
                <h4><i class="fas fa-receipt"></i> Résumé de la commande</h4>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total :</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} F CFA</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Livraison :</span>
                    <span>Gratuite</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total :</strong>
                    <strong>{{ number_format($total, 0, ',', ' ') }} F CFA</strong>
                </div>
                <button type="submit" form="orderForm" class="btn btn-light btn-lg w-100">
                    <i class="fas fa-check"></i> Valider la commande
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function selectPayment(method) {
    // Désélectionner toutes les cartes
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Sélectionner la carte cliquée
    event.currentTarget.classList.add('selected');
    
    // Cocher le radio button correspondant
    document.getElementById(method).checked = true;
}

// Validation du formulaire
document.getElementById('orderForm').addEventListener('submit', function(e) {
    const paiement = document.querySelector('input[name="paiement"]:checked');
    if (!paiement) {
        e.preventDefault();
        alert('Veuillez sélectionner un mode de paiement.');
        return false;
    }
});
</script>
</body>
</html> 