<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $statusLabels = [
            'pending' => 'En attente',
            'processing' => 'En cours de traitement',
            'shipped' => 'Expédiée',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
        ];

        return $this->subject('Mise à jour de votre commande - ' . $this->order->order_number)
                    ->view('emails.order-status-update')
                    ->with([
                        'order' => $this->order,
                        'orderNumber' => $this->order->order_number,
                        'customerName' => $this->order->user->name,
                        'oldStatus' => $statusLabels[$this->oldStatus] ?? $this->oldStatus,
                        'newStatus' => $statusLabels[$this->newStatus] ?? $this->newStatus,
                    ]);
    }
} 