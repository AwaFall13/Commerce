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

// Routes d'authentification
Route::get('/connexion', function () {
    return view('connexion');
})->name('login');

Route::get('/inscription', function () {
    return view('inscription');
})->name('register');

Route::post('/connexion', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/inscription', [App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
Route::post('/deconnexion', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout.post');
Route::get('/deconnexion', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/accueil', [App\Http\Controllers\HomeController::class, 'index'])->name('accueil');

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

// Routes pour le système de commandes
Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/place', [App\Http\Controllers\OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order/confirmation/{id}', [App\Http\Controllers\OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/order/history', [App\Http\Controllers\OrderController::class, 'myOrders'])->name('order.history');
Route::get('/order/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('order.details');
Route::get('/order/{id}/invoice', [App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('order.invoice.download');
Route::get('/payment/{id}/process', [App\Http\Controllers\OrderController::class, 'processPayment'])->name('payment.process');

// Modifier la route panier pour ajouter un lien vers le checkout
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
        \Log::error('Panier vide');
        return redirect('/panier')->with('error', 'Votre panier est vide.');
    }
    
    $total = collect($panier)->sum(function($item) {
        return $item['price'] * $item['quantity'];
    });
    
    $adresse = $request->input('adresse');
    $paiement = $request->input('paiement', 'à la livraison');
    $payment_method = $paiement === 'en ligne' ? 'online' : 'cash_on_delivery';
    $payment_status = $paiement === 'en ligne' ? 'paid' : 'pending';
                        $user_id = session('user_id');
    
    if (!$user_id) {
        \Log::error('Utilisateur non connecté');
        return redirect('/connexion')->with('error', 'Vous devez être connecté pour valider la commande.');
    }
    
    try {
        // Générer un numéro de commande unique
        $order_number = 'CMD-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        $order = \App\Models\Order::create([
            'user_id' => $user_id,
            'order_number' => $order_number,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_method' => $payment_method,
            'payment_status' => $payment_status,
            'shipping_address' => $adresse,
            'shipping_city' => 'Dakar', // Valeur par défaut
            'shipping_postal_code' => '10000', // Valeur par défaut
            'shipping_phone' => '777777777', // Valeur par défaut
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
        
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la création de commande', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect('/panier')->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
    }
})->name('panier.valider.post');

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
    
    // Sauvegarder le message en base de données
    \App\Models\Contact::create([
        'name' => $request->name,
        'email' => $request->email,
        'message' => $request->message,
        'created_at' => now(),
    ]);
    
    return back()->with('success', 'Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.');
})->name('contact.post');

// Routes publiques
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('accueil');

Route::get('/catalogue', [App\Http\Controllers\ProductController::class, 'catalogue'])->name('catalogue');

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

// Routes d'administration
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/commandes', [App\Http\Controllers\AdminController::class, 'ordersWeb'])->name('admin.orders');
Route::get('/admin/commandes/{id}/status/{status}', [App\Http\Controllers\AdminController::class, 'updateOrderStatus'])->name('admin.order.status');
Route::get('/admin/commandes/{id}/pay', [App\Http\Controllers\AdminController::class, 'markOrderAsPaid'])->name('admin.order.pay');
Route::get('/admin/messages', [App\Http\Controllers\AdminController::class, 'messages'])->name('admin.messages');

// Route pour mettre à jour les images avec des URLs spécifiques aux produits africains
Route::get('/update-african-images', function () {
    $map = [
        'Boucles d\'oreilles wax' => 'https://images.unsplash.com/photo-1515562141207-7db88e9bdc18?w=300&h=300&fit=crop',
        'Bissap' => 'https://images.unsplash.com/photo-1546173159-315724a31696?w=300&h=300&fit=crop',
        'Pagnes tissés' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=300&h=300&fit=crop',
        'Savon noir africain' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=300&fit=crop',
        'Thiouraye' => 'https://images.unsplash.com/photo-1602928327479-9b3c3b0b0b0b?w=300&h=300&fit=crop',
        'Sandales artisanales' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=300&h=300&fit=crop',
        'Baobab en poudre' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=300&h=300&fit=crop',
        'Panier en osier' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=300&h=300&fit=crop',
    ];

    foreach ($map as $nom => $url) {
        $p = \App\Models\Product::where('name', $nom)->first();
        if ($p) {
            $p->image = $url;
            $p->save();
            echo "Produit $nom mis à jour avec l'image $url<br>";
        }
    }
    
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});

// Route pour mettre à jour avec des images locales
Route::get('/update-local-images', function () {
    $map = [
        'Boucles d\'oreilles wax' => 'bijoux-africains.jpg',
        'Bissap' => 'bissap-boisseau.jpg',
        'Pagnes tissés' => 'pagnes-africains.jpg',
        'Savon noir africain' => 'savon-noir.jpg',
        'Thiouraye' => 'encens-thiouraye.jpg',
        'Sandales artisanales' => 'sandales-artisanales.jpg',
        'Baobab en poudre' => 'baobab-poudre.jpg',
        'Panier en osier' => 'panier-osier.jpg',
    ];

    foreach ($map as $nom => $image) {
        $p = \App\Models\Product::where('name', $nom)->first();
        if ($p) {
            $p->image = $image;
            $p->save();
            echo "Produit $nom mis à jour avec l'image locale: $image<br>";
        }
    }
    
    echo "<br><strong>IMPORTANT:</strong> Placez les images suivantes dans le dossier public/images/ :<br>";
    foreach ($map as $nom => $image) {
        echo "- $image (pour $nom)<br>";
    }
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});

// Route pour associer les images existantes aux produits
Route::get('/associate-existing-images', function () {
    $map = [
        'Boucles d\'oreilles wax' => '7e44fd47ad1a3ff777c7c38b178f0692.jpg',
        'Bissap' => 'bissap1.png',
        'Pagnes tissés' => 'pagnes.png',
        'Savon noir africain' => 'savon-noir-africa-in-ose-dudu-61088214.webp',
        'Thiouraye' => 'OIP (3).webp',
        'Sandales artisanales' => 'OIP (2).webp',
        'Baobab en poudre' => 'Sanstitre-2022-01-11T160209.421.webp',
        'Panier en osier' => 'fait-a-la-main-de-s-paniers-en-osier-jx7xw9.jpg',
    ];

    foreach ($map as $nom => $image) {
        $p = \App\Models\Product::where('name', $nom)->first();
        if ($p) {
            $p->image = $image;
            $p->save();
            echo "Produit $nom associé à l'image: $image<br>";
        }
    }
    
    echo "<br><strong>Images associées avec succès !</strong><br>";
    echo "<a href='/catalogue'>Voir le catalogue</a>";
});

// Route pour associer les images renommées aux produits
Route::get('/associate-renamed-images', function () {
    $map = [
        'Boucles d\'oreilles wax' => 'boucles d\'oreilles.jpg',
        'Bissap' => 'bissap.webp',
        'Pagnes tissés' => 'Pagnes tissés.webp',
        'Savon noir africain' => 'Savon noir africain.webp',
        'Thiouraye' => 'thiouraye.webp',
        'Sandales artisanales' => 'Sandales artisanales.webp',
        'Baobab en poudre' => 'Baobab en poudre.webp',
        'Panier en osier' => 'panier en osier.jpg',
    ];

    foreach ($map as $nom => $image) {
        $p = \App\Models\Product::where('name', $nom)->first();
        if ($p) {
            $p->image = $image;
            $p->save();
            echo "Produit $nom associé à l'image: $image<br>";
        }
    }
    
    echo "<br><strong>Images renommées associées avec succès !</strong><br>";
    echo "<a href='/catalogue'>Voir le catalogue</a>";
});

// Route de debug pour vérifier les images produits
Route::get('/debug-images', function () {
    $products = \App\Models\Product::all();
    echo '<style>img.debug-img{max-height:80px;display:block;margin:0 auto;object-fit:contain;}</style>';
    echo '<table border="1" cellpadding="5"><tr><th>Nom</th><th>Champ image</th><th>URL générée</th><th>Image</th></tr>';
    foreach ($products as $product) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($product->name) . '</td>';
        echo '<td>' . htmlspecialchars($product->image) . '</td>';
        echo '<td>' . htmlspecialchars($product->image_url) . '</td>';
        echo '<td><img src="' . $product->image_url . '" class="debug-img"></td>';
        echo '</tr>';
    }
    echo '</table>';
});

// Route pour corriger l'image des boucles d'oreilles
Route::get('/fix-earrings-image', function () {
    // Vérifier d'abord quel fichier existe pour les boucles d'oreilles
    $possibleFiles = [
        'boucles d\'oreilles.jpg',
        'boucles d\'oreilles.webp',
        'boucles.jpg',
        'boucles.webp',
        'earrings.jpg',
        'earrings.webp'
    ];
    
    echo "<h3>Fichiers possibles pour les boucles d'oreilles :</h3>";
    foreach ($possibleFiles as $file) {
        $exists = file_exists(public_path('images/' . $file));
        echo "- $file : " . ($exists ? "EXISTE" : "N'existe pas") . "<br>";
    }
    
    // Corriger l'association
    $p = \App\Models\Product::where('name', 'Boucles d\'oreilles wax')->first();
    if ($p) {
        // Essayer de trouver le bon fichier
        $correctFile = null;
        foreach ($possibleFiles as $file) {
            if (file_exists(public_path('images/' . $file))) {
                $correctFile = $file;
                break;
            }
        }
        
        if ($correctFile) {
            $p->image = $correctFile;
            $p->save();
            echo "<br><strong>Produit 'Boucles d\'oreilles wax' associé à : $correctFile</strong><br>";
        } else {
            echo "<br><strong>Aucun fichier image trouvé pour les boucles d'oreilles</strong><br>";
        }
    }
    
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});

// Route pour corriger spécifiquement l'image des boucles d'oreilles
Route::get('/fix-boucles-oreilles', function () {
    $p = \App\Models\Product::where('name', 'Boucles d\'oreilles wax')->first();
    
    if ($p) {
        // Vérifier si le fichier existe
        $imageFile = 'boucles d\'oreilles.jpg';
        $fileExists = file_exists(public_path('images/' . $imageFile));
        
        echo "Fichier recherché : $imageFile<br>";
        echo "Fichier existe : " . ($fileExists ? "OUI" : "NON") . "<br>";
        
        if ($fileExists) {
            $p->image = $imageFile;
            $p->save();
            echo "<br><strong>✅ Produit 'Boucles d\'oreilles wax' associé à : $imageFile</strong><br>";
        } else {
            echo "<br><strong>❌ Fichier non trouvé : $imageFile</strong><br>";
        }
    } else {
        echo "<br><strong>❌ Produit 'Boucles d\'oreilles wax' non trouvé</strong><br>";
    }
    
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});

// Route pour corriger l'image des boucles d'oreilles avec le bon nom
Route::get('/fix-boucles-exact', function () {
    $p = \App\Models\Product::where('name', 'Boucles d\'oreilles wax')->first();
    
    if ($p) {
        // Vérifier si le fichier existe
        $imageFile = 'boucles d\'oreilles.jpg';
        $fileExists = file_exists(public_path('images/' . $imageFile));
        
        echo "Produit trouvé : {$p->name} (ID: {$p->id})<br>";
        echo "Ancienne image : {$p->image}<br>";
        echo "Nouvelle image : $imageFile<br>";
        echo "Fichier existe : " . ($fileExists ? "OUI" : "NON") . "<br>";
        
        if ($fileExists) {
            $p->image = $imageFile;
            $p->save();
            echo "<br><strong>✅ Produit 'Boucles d\'oreilles wax' mis à jour avec : $imageFile</strong><br>";
        } else {
            echo "<br><strong>❌ Fichier non trouvé : $imageFile</strong><br>";
        }
    } else {
        echo "<br><strong>❌ Produit 'Boucles d\'oreilles wax' non trouvé</strong><br>";
    }
    
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});

// Route pour vérifier les noms exacts des produits dans la base
Route::get('/check-product-names', function () {
    $products = \App\Models\Product::all();
    
    echo "<h3>Noms des produits dans la base de données :</h3>";
    foreach ($products as $product) {
        echo "- ID: {$product->id} | Nom: '{$product->name}' | Image: '{$product->image}'<br>";
    }
    
    echo "<br><h3>Recherche spécifique pour les boucles d'oreilles :</h3>";
    $earrings = \App\Models\Product::where('name', 'like', '%boucle%')->orWhere('name', 'like', '%oreille%')->get();
    
    if ($earrings->count() > 0) {
        foreach ($earrings as $product) {
            echo "- Trouvé: '{$product->name}' (ID: {$product->id})<br>";
        }
    } else {
        echo "- Aucun produit trouvé avec 'boucle' ou 'oreille' dans le nom<br>";
    }
    
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});

// Route simple pour corriger l'image des boucles d'oreilles par ID
Route::get('/fix-boucles-simple', function () {
    try {
        // Utiliser l'ID 7 que nous avons vu dans la liste
        $p = \App\Models\Product::find(7);
        
        if ($p) {
            echo "Produit trouvé : {$p->name} (ID: {$p->id})<br>";
            echo "Ancienne image : {$p->image}<br>";
            
            // Vérifier si le fichier existe
            $imageFile = 'boucles d\'oreilles.jpg';
            $fileExists = file_exists(public_path('images/' . $imageFile));
            echo "Fichier $imageFile existe : " . ($fileExists ? "OUI" : "NON") . "<br>";
            
            if ($fileExists) {
                $p->image = $imageFile;
                $p->save();
                echo "<br><strong>✅ SUCCÈS : Image mise à jour avec $imageFile</strong><br>";
            } else {
                echo "<br><strong>❌ ERREUR : Fichier $imageFile non trouvé</strong><br>";
            }
        } else {
            echo "<br><strong>❌ ERREUR : Produit avec ID 7 non trouvé</strong><br>";
        }
    } catch (Exception $e) {
        echo "<br><strong>❌ ERREUR : " . $e->getMessage() . "</strong><br>";
    }
    
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});

// Route pour lister tous les fichiers dans le dossier images
Route::get('/list-images', function () {
    $imagePath = public_path('images/');
    $files = scandir($imagePath);
    
    echo "<h3>Fichiers dans le dossier public/images/ :</h3>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $fullPath = $imagePath . $file;
            $exists = file_exists($fullPath);
            $size = $exists ? filesize($fullPath) : 0;
            echo "- $file (taille: " . number_format($size) . " bytes) - " . ($exists ? "EXISTE" : "N'EXISTE PAS") . "<br>";
        }
    }
    
    echo "<br><h3>Recherche spécifique pour les boucles d'oreilles :</h3>";
    $earringFiles = [];
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && 
            (stripos($file, 'boucle') !== false || stripos($file, 'oreille') !== false || stripos($file, 'earring') !== false)) {
            $earringFiles[] = $file;
        }
    }
    
    if (count($earringFiles) > 0) {
        foreach ($earringFiles as $file) {
            echo "- $file<br>";
        }
    } else {
        echo "- Aucun fichier trouvé contenant 'boucle', 'oreille' ou 'earring'<br>";
    }
    
    echo "<br><a href='/catalogue'>Voir le catalogue</a>";
});
