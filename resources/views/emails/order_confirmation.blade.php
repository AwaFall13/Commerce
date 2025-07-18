@component('mail::message')
# Merci pour votre commande !

Bonjour {{ $order->user->name }},

Votre commande n°{{ $order->id }} a bien été enregistrée.

**Détail de la commande :**

@component('mail::table')
| Produit | Quantité | Prix unitaire | Total |
|---------|----------|---------------|-------|
@foreach($order->orderItems as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | {{ number_format($item->price, 0, ',', ' ') }} F CFA | {{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F CFA |
@endforeach
@endcomponent

**Total TTC :** {{ number_format($order->total, 0, ',', ' ') }} F CFA

Adresse de livraison : {{ $order->address }}

Merci pour votre confiance !

@endcomponent
