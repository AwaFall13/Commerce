<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Voir le panier de l'utilisateur connecté
    public function show()
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $items = $cart->cartItems()->with('product')->get();
        return response()->json(['cart' => $cart, 'items' => $items]);
    }

    // Ajouter un produit au panier
    public function add(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();
        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        return response()->json(['message' => 'Produit ajouté au panier']);
    }

    // Modifier la quantité d'un produit
    public function update(Request $request, $itemId)
    {
        $item = CartItem::find($itemId);
        if ($item) {
            $item->quantity = $request->quantity;
            $item->save();
            return response()->json(['message' => 'Quantité modifiée']);
        }
        return response()->json(['message' => 'Produit non trouvé dans le panier'], 404);
    }

    // Supprimer un produit du panier
    public function remove($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item) {
            $item->delete();
            return response()->json(['message' => 'Produit supprimé du panier']);
        }
        return response()->json(['message' => 'Produit non trouvé dans le panier'], 404);
    }

    // Vider le panier
    public function clear()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        if ($cart) {
            $cart->cartItems()->delete();
        }
        return response()->json(['message' => 'Panier vidé']);
    }
}
