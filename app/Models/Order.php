<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status', // 'pending', 'processing', 'shipped', 'delivered', 'cancelled'
        'payment_method', // 'online', 'cash_on_delivery'
        'payment_status', // 'pending', 'paid', 'failed'
        'total_amount',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_phone',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Accesseurs pour les statuts
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'En attente',
            'processing' => 'En cours de traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
        ][$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        return [
            'pending' => 'En attente',
            'paid' => 'Payé',
            'failed' => 'Échoué',
        ][$this->payment_status] ?? $this->payment_status;
    }

    public function getPaymentMethodLabelAttribute()
    {
        return [
            'online' => 'Paiement en ligne',
            'cash_on_delivery' => 'Paiement à la livraison',
        ][$this->payment_method] ?? $this->payment_method;
    }

    // Méthodes pour vérifier les statuts
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function canBeShipped()
    {
        return $this->status === 'pending' && $this->payment_status === 'paid';
    }
}
