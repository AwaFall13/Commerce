<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Finaliser la commande</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
@include('layouts.header')
<div class="container mt-4">
    <h1 class="mb-4">Finaliser votre commande</h1>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Informations de livraison</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('order.place') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="shipping_address" class="form-label">Adresse de livraison *</label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                          id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_city" class="form-label">Ville *</label>
                                <input type="text" class="form-control @error('shipping_city') is-invalid @enderror" 
                                       id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required>
                                @error('shipping_city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_postal_code" class="form-label">Code postal *</label>
                                <input type="text" class="form-control @error('shipping_postal_code') is-invalid @enderror" 
                                       id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" required>
                                @error('shipping_postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_phone" class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control @error('shipping_phone') is-invalid @enderror" 
                                   id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}" required>
                            @error('shipping_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Mode de paiement *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="online" value="online" required>
                                <label class="form-check-label" for="online">
                                    <strong>Paiement en ligne</strong> - Paiement sécurisé par carte bancaire
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery" required>
                                <label class="form-check-label" for="cash_on_delivery">
                                    <strong>Paiement à la livraison</strong> - Vous paierez en espèces à la réception
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">Confirmer la commande</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Récapitulatif de votre commande</h5>
                </div>
                <div class="card-body">
                    @foreach($panier as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <strong>{{ $item['name'] }}</strong><br>
                                <small class="text-muted">Quantité: {{ $item['quantity'] }}</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} F CFA</strong>
                            </div>
                        </div>
                    @endforeach
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5>Total</h5>
                        <h5>{{ number_format($total, 0, ',', ' ') }} F CFA</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 