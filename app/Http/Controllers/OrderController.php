<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id,
            'total' => $request->total,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'is_paid' => $request->is_paid,
            'address' => $request->address,
        ]);
        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }
        return response()->json($order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }
        $order->update($request->all());
        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }
        $order->delete();
        return response()->json(['message' => 'Commande supprimée']);
    }

    // Passer une commande à partir du panier
    public function placeOrder()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        if (!$cart || $cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Panier vide'], 400);
        }
        $total = 0;
        foreach ($cart->cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }
        $order = $user->orders()->create([
            'total' => $total,
            'status' => 'en attente',
            'payment_method' => 'à la livraison',
            'is_paid' => false,
            'address' => 'Adresse à renseigner',
        ]);
        foreach ($cart->cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }
        $cart->cartItems()->delete();
        // Envoi de l'e-mail de confirmation
        Mail::to($user->email)->send(new OrderConfirmationMail($order));
        return response()->json(['message' => 'Commande passée avec succès', 'order' => $order]);
    }

    // Historique des commandes de l'utilisateur avec pagination et filtres
    public function myOrders(Request $request)
    {
        $user = Auth::user();
        $query = $user->orders()->with('orderItems.product');
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $orders = $query->orderByDesc('created_at')->paginate(10);
        return response()->json($orders);
    }

    // Télécharger la facture PDF d'une commande
    public function downloadInvoice($orderId)
    {
        $order = Order::with(['user', 'orderItems.product'])->find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Commande non trouvée'], 404);
        }
        $pdf = Pdf::loadView('invoice', compact('order'));
        return $pdf->download('facture_commande_'.$order->id.'.pdf');
    }
}
