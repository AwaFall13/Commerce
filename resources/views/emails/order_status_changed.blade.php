@component('mail::message')
# Mise à jour de votre commande

Bonjour {{ $order->user->name }},

Le statut de votre commande n°{{ $order->id }} a changé.

**Nouveau statut :** {{ ucfirst($newStatus) }}

@component('mail::button', ['url' => url('/api/orders/'.$order->id.'/invoice')])
Télécharger la facture
@endcomponent

Merci pour votre confiance !

@endcomponent 