<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Mail\OrderStatusChangedMail;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    // Lister tous les utilisateurs
    public function users()
    {
        return response()->json(User::all());
    }

    // Lister toutes les commandes avec pagination et filtres
    public function orders(Request $request)
    {
        $query = Order::with('user', 'orderItems.product');
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $orders = $query->orderByDesc('created_at')->paginate(10);
        return response()->json($orders);
    }

    // Modifier le statut d'une commande
    public function updateOrderStatus(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }
        $order->status = $request->status;
        $order->save();
        // Envoi de l'e-mail de notification au client
        Mail::to($order->user->email)->send(new OrderStatusChangedMail($order, $request->status));
        return response()->json(['message' => 'Statut de la commande mis à jour', 'order' => $order]);
    }

    // Marquer une commande comme payée
    public function markOrderPaid($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }
        $order->is_paid = true;
        $order->save();
        return response()->json(['message' => 'Commande marquée comme payée', 'order' => $order]);
    }

    // Statistiques du dashboard admin
    public function dashboard()
    {
        $chiffreAffaires = \App\Models\Order::where('is_paid', true)->sum('total');
        $nbCommandes = \App\Models\Order::count();
        $nbClients = \App\Models\User::where('is_admin', false)->count();
        $produitsPlusVendus = \App\Models\OrderItem::select('product_id', \DB::raw('SUM(quantity) as total'))
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->with('product')
            ->take(5)
            ->get();
        $paiements = [
            'payees' => \App\Models\Order::where('is_paid', true)->count(),
            'non_payees' => \App\Models\Order::where('is_paid', false)->count(),
        ];
        return response()->json([
            'chiffre_affaires' => $chiffreAffaires,
            'nb_commandes' => $nbCommandes,
            'nb_clients' => $nbClients,
            'produits_plus_vendus' => $produitsPlusVendus,
            'paiements' => $paiements,
        ]);
    }

    // Afficher la liste des commandes pour la vue admin
    public function commandesWeb()
    {
        $commandes = \App\Models\Order::with('user')->orderByDesc('created_at')->paginate(10);
        return view('admin.commandes.index', compact('commandes'));
    }
}
