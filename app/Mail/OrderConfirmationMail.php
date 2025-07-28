<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre commande - ' . $this->order->order_number)
                    ->view('emails.order-confirmation')
                    ->with([
                        'order' => $this->order,
                        'orderNumber' => $this->order->order_number,
                        'customerName' => $this->order->user->name,
                        'totalAmount' => number_format($this->order->total_amount, 0, ',', ' ') . ' F CFA',
                        'paymentMethod' => $this->order->payment_method_label,
                    ]);
    }
}
