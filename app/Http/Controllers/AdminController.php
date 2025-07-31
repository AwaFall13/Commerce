<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Category;
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
        $totalProducts = \App\Models\Product::count();

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

    // ==================== GESTION DES UTILISATEURS ====================

    /**
     * Afficher la liste des utilisateurs
     */
    public function usersWeb()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Afficher les détails d'un utilisateur
     */
    public function showUser($id)
    {
        $user = User::with(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Afficher le formulaire de modification d'un utilisateur
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'is_admin' => 'boolean'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->has('is_admin')
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Supprimer un utilisateur
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher la suppression de l'admin principal
        if ($user->email === 'admin@ecommerce.com') {
            return redirect()->route('admin.users')
                ->with('error', 'Impossible de supprimer l\'administrateur principal.');
        }

        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    // ==================== GESTION DES CATÉGORIES ====================

    /**
     * Afficher la liste des catégories
     */
    public function categoriesWeb()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Afficher le formulaire d'ajout de catégorie
     */
    public function addCategoryForm()
    {
        return view('admin.categories.add');
    }

    /**
     * Ajouter une nouvelle catégorie
     */
    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Catégorie ajoutée avec succès.');
    }

    /**
     * Afficher le formulaire de modification de catégorie
     */
    public function editCategoryForm($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Mettre à jour une catégorie
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:1000'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('admin.categories')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Supprimer une catégorie
     */
    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Vérifier s'il y a des produits dans cette catégorie
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories')
                ->with('error', 'Impossible de supprimer une catégorie qui contient des produits.');
        }

        $category->delete();
        
        return redirect()->route('admin.categories')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
