<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\OrderStatusChangedMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdateMail;

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

    /**
     * Afficher la liste des commandes
     */
    public function ordersWeb()
    {
        $orders = Order::with(['user', 'orderItems.product'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Modifier le statut d'une commande
     */
    public function updateOrderStatus($id, $status)
    {
        $order = Order::with('user')->findOrFail($id);
        $oldStatus = $order->status;
        
        $order->update(['status' => $status]);
        
        // Envoyer l'email de mise à jour de statut
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdateMail($order, $oldStatus, $status));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email mise à jour statut: ' . $e->getMessage());
        }
        
        return redirect()->route('admin.orders')->with('success', 'Statut de la commande mis à jour avec succès.');
    }

    /**
     * Marquer une commande comme payée
     */
    public function markOrderAsPaid($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => 'paid']);
        
        return redirect()->route('admin.orders')->with('success', 'Commande marquée comme payée.');
    }

    /**
     * Afficher le tableau de bord avec statistiques
     */
    public function dashboard()
    {
        // Statistiques générales
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalCustomers = User::where('is_admin', false)->count();
        $totalProducts = Product::count();

        // Commandes récentes
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->limit(10)->get();

        // Statistiques de paiement
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $pendingOrders = Order::where('payment_status', 'pending')->count();
        $failedOrders = Order::where('payment_status', 'failed')->count();

        // Graphique des ventes (7 derniers jours)
        $salesChartLabels = [];
        $salesChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $salesChartLabels[] = $date->format('d/m');
            $salesChartData[] = Order::where('payment_status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
        }

        // Produits les plus vendus
        $topProducts = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', \DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        $topProductsLabels = $topProducts->pluck('name')->toArray();
        $topProductsData = $topProducts->pluck('total_sold')->toArray();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'totalProducts',
            'recentOrders',
            'paidOrders',
            'pendingOrders',
            'failedOrders',
            'salesChartLabels',
            'salesChartData',
            'topProductsLabels',
            'topProductsData'
        ));
    }

    /**
     * Afficher les messages de contact
     */
    public function messages()
    {
        $messages = Contact::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.messages.index', compact('messages'));
    }
}
