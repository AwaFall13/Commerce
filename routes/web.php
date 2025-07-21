<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/accueil', function () {
    $categories = Category::all();
    $produits = Product::orderByDesc('created_at')->take(4)->get();
    return view('accueil', compact('categories', 'produits'));
})->name('accueil');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produits/{id}', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    return view('fiche_produit', compact('product'));
})->name('produit.detail');

Route::post('/panier/ajouter', function (Request $request) {
    $product = \App\Models\Product::findOrFail($request->product_id);
    $quantity = max(1, (int)$request->quantity);
    $panier = session()->get('panier', []);
    if (isset($panier[$product->id])) {
        $panier[$product->id]['quantity'] += $quantity;
    } else {
        $panier[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'quantity' => $quantity,
        ];
    }
    session(['panier' => $panier]);
    return redirect('/panier')->with('success', 'Produit ajouté au panier !');
})->name('panier.ajouter');

Route::post('/panier/modifier', function (Request $request) {
    $panier = session()->get('panier', []);
    $id = $request->product_id;
    $quantity = max(1, (int)$request->quantity);
    if (isset($panier[$id])) {
        $panier[$id]['quantity'] = $quantity;
        session(['panier' => $panier]);
    }
    return redirect('/panier')->with('success', 'Quantité modifiée !');
})->name('panier.modifier');

Route::post('/panier/supprimer', function (Request $request) {
    $panier = session()->get('panier', []);
    $id = $request->product_id;
    if (isset($panier[$id])) {
        unset($panier[$id]);
        session(['panier' => $panier]);
    }
    return redirect('/panier')->with('success', 'Produit supprimé du panier !');
})->name('panier.supprimer');

Route::get('/panier', function () {
    $panier = session('panier', []);
    $total = collect($panier)->sum(function($item) {
        return $item['price'] * $item['quantity'];
    });
    return view('panier', compact('panier', 'total'));
})->name('panier');

Route::get('/panier/valider', function () {
    $panier = session('panier', []);
    $total = collect($panier)->sum(function($item) {
        return $item['price'] * $item['quantity'];
    });
    return view('valider_panier', compact('panier', 'total'));
})->name('panier.valider');

Route::post('/panier/valider', function (Request $request) {
    $panier = session('panier', []);
    if (empty($panier)) {
        return redirect('/panier')->with('error', 'Votre panier est vide.');
    }
    $total = collect($panier)->sum(function($item) {
        return $item['price'] * $item['quantity'];
    });
    $adresse = $request->input('adresse');
    $paiement = $request->input('paiement', 'à la livraison');
    $is_paid = $paiement === 'en ligne';
    $user_id = session('user_id');
    if (!$user_id) {
        return redirect('/connexion')->with('error', 'Vous devez être connecté pour valider la commande.');
    }
    $order = \App\Models\Order::create([
        'user_id' => $user_id,
        'total' => $total,
        'status' => 'en attente',
        'payment_method' => $paiement,
        'is_paid' => $is_paid,
        'address' => $adresse,
    ]);
    foreach ($panier as $item) {
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }
    session()->forget('panier');
    return redirect('/')->with('success', 'Commande validée avec succès !');
})->name('panier.valider.post');

Route::get('/inscription', function () {
    return view('inscription');
})->name('inscription');

Route::post('/inscription', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
    ]);
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);
    session(['user_id' => $user->id]);
    return redirect('/')->with('success', 'Inscription réussie !');
});

Route::get('/connexion', function () {
    return view('connexion');
})->name('connexion');

Route::post('/connexion', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    $user = User::where('email', $request->email)->first();
    if ($user && Hash::check($request->password, $user->password)) {
        session(['user_id' => $user->id]);
        return redirect('/')->with('success', 'Connexion réussie !');
    }
    return back()->withErrors(['email' => 'Identifiants incorrects']);
});

Route::get('/deconnexion', function () {
    session()->forget('user_id');
    return redirect('/')->with('success', 'Déconnexion réussie !');
})->name('deconnexion');

Route::get('/mon-compte', function () {
    $user = null;
    $commandes = [];
    if (session('user_id')) {
        $user = \App\Models\User::find(session('user_id'));
        // Pour la démo, on simule l'historique (tu peux utiliser l'API ou la base réelle)
        $commandes = $user->orders()->with('orderItems.product')->orderByDesc('created_at')->get();
    }
    return view('mon_compte', compact('user', 'commandes'));
})->name('mon-compte');

Route::post('/mon-compte/modifier', function (Request $request) {
    if (!session('user_id')) {
        return redirect('/connexion');
    }
    $user = \App\Models\User::find(session('user_id'));
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
    ]);
    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->filled('password')) {
        $user->password = \Hash::make($request->password);
    }
    $user->save();
    return redirect('/mon-compte')->with('success', 'Profil mis à jour !');
})->name('mon-compte.modifier');

Route::get('/mon-compte/commande/{id}', function ($id) {
    if (!session('user_id')) {
        return redirect('/connexion');
    }
    $commande = \App\Models\Order::with('orderItems.product')->findOrFail($id);
    if ($commande->user_id != session('user_id')) {
        abort(403, 'Accès interdit');
    }
    return view('detail_commande', compact('commande'));
})->name('mon-compte.commande');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required',
    ]);
    // Ici, tu pourrais envoyer un email avec Mail::to(...)->send(...)
    return back()->with('success', 'Votre message a bien été envoyé !');
})->name('contact.post');

Route::get('/catalogue', function (Request $request) {
    $query = Product::query();
    if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    $products = $query->orderByDesc('created_at')->paginate(12);
    return view('catalogue', compact('products'));
})->name('catalogue');

Route::get('/admin/produits', function () {
    $user = null;
    if (session('user_id')) {
        $user = \App\Models\User::find(session('user_id'));
    }
    if (!$user || !$user->is_admin) {
        abort(403, 'Accès réservé à l\'administrateur');
    }
    $produits = \App\Models\Product::with('category')->orderByDesc('created_at')->paginate(10);
    return view('admin.produits.index', compact('produits'));
})->name('admin.produits');

Route::get('/admin/produits/ajouter', function () {
    $user = null;
    if (session('user_id')) {
        $user = \App\Models\User::find(session('user_id'));
    }
    if (!$user || !$user->is_admin) {
        abort(403, 'Accès réservé à l\'administrateur');
    }
    $categories = \App\Models\Category::all();
    return view('admin.produits.ajouter', compact('categories'));
})->name('admin.produits.ajouter');

Route::post('/admin/produits/ajouter', function (Request $request) {
    $user = null;
    if (session('user_id')) {
        $user = \App\Models\User::find(session('user_id'));
    }
    if (!$user || !$user->is_admin) {
        abort(403, 'Accès réservé à l\'administrateur');
    }
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image',
    ]);
    $data = $request->all();
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $data['image'] = '/storage/' . $path;
    }
    \App\Models\Product::create($data);
    return redirect()->route('admin.produits')->with('success', 'Produit ajouté !');
})->name('admin.produits.ajouter.post');

// Route pour afficher la liste des commandes côté admin
Route::get('/admin/commandes', [App\Http\Controllers\AdminController::class, 'commandesWeb'])->name('admin.commandes');
// Route pour marquer une commande comme payée
Route::post('/admin/commandes/{id}/pay', function($id) {
    $order = \App\Models\Order::find($id);
    if ($order && !$order->is_paid) {
        $order->is_paid = true;
        $order->save();
    }
    return redirect()->back();
})->name('admin.commandes.pay');
