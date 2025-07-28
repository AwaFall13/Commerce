<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Mail\OrderStatusUpdateMail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Afficher le formulaire de commande
     */
    public function checkout()
    {
        $panier = session('panier', []);
        if (empty($panier)) {
            return redirect('/panier')->with('error', 'Votre panier est vide.');
        }

        $total = collect($panier)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('checkout', compact('panier', 'total'));
    }

    /**
     * Traiter la commande
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'shipping_phone' => 'required|string',
            'payment_method' => 'required|in:online,cash_on_delivery',
            'notes' => 'nullable|string',
        ]);

        $panier = session('panier', []);
        if (empty($panier)) {
            return redirect('/panier')->with('error', 'Votre panier est vide.');
        }

        $total = collect($panier)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });

        // Vérifier le stock
        foreach ($panier as $item) {
            $product = Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                return redirect('/panier')->with('error', "Stock insuffisant pour {$product->name}.");
            }
        }

        DB::beginTransaction();
        try {
            // Créer la commande
            $order = Order::create([
                'user_id' => session('user_id'),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'online' ? 'pending' : 'pending',
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_phone' => $request->shipping_phone,
                'notes' => $request->notes,
            ]);

            // Créer les éléments de commande
            foreach ($panier as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Mettre à jour le stock
                $product = Product::find($item['id']);
                $product->decrement('stock', $item['quantity']);
            }

            // Vider le panier
            session()->forget('panier');

            // Envoyer l'email de confirmation
            try {
                Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas faire échouer la commande
                \Log::error('Erreur envoi email confirmation: ' . $e->getMessage());
            }

            DB::commit();

            // Rediriger selon le mode de paiement
            if ($request->payment_method === 'online') {
                return redirect()->route('payment.process', $order->id);
            } else {
                return redirect()->route('order.confirmation', $order->id)
                    ->with('success', 'Votre commande a été enregistrée. Vous paierez à la livraison.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/panier')->with('error', 'Erreur lors de la création de la commande.');
        }
    }

    /**
     * Afficher la confirmation de commande
     */
    public function confirmation($orderId)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($orderId);
        
        // Vérifier que l'utilisateur connecté est bien le propriétaire de la commande
        if ($order->user_id !== session('user_id')) {
            abort(403);
        }

        return view('order.confirmation', compact('order'));
    }

    /**
     * Historique des commandes du client
     */
    public function myOrders()
    {
        $user_id = session('user_id');
        
        if (!$user_id) {
            return redirect('/connexion')->with('error', 'Vous devez être connecté pour voir vos commandes.');
        }

        $orders = Order::with(['orderItems.product'])
            ->where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('order.history', compact('orders'));
    }

    /**
     * Détails d'une commande
     */
    public function show($orderId)
    {
        $order = Order::with(['orderItems.product', 'user'])->findOrFail($orderId);
        
        // Vérifier que l'utilisateur connecté est bien le propriétaire de la commande
        if ($order->user_id !== session('user_id')) {
            abort(403);
        }

        return view('order.details', compact('order'));
    }

    /**
     * Télécharger la facture PDF
     */
    public function downloadInvoice($orderId)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($orderId);
        
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        
        return $pdf->download('facture-' . $order->order_number . '.pdf');
    }

    /**
     * Traitement du paiement en ligne (simulation)
     */
    public function processPayment($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        // Simulation du paiement en ligne
        $oldStatus = $order->status;
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing'
        ]);

        // Envoyer l'email de mise à jour de statut
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdateMail($order, $oldStatus, $order->status));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email mise à jour statut: ' . $e->getMessage());
        }

        return redirect()->route('order.confirmation', $order->id)
            ->with('success', 'Paiement effectué avec succès !');
    }
}
