<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Category; // Added this import for the catalogue function

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%") ;
            });
        }
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        $products = $query->paginate(10);
        return response()->json($products);
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
        $data = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        }
        $product = Product::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'image' => $data['image'] ?? null,
            'category_id' => $data['category_id'],
        ]);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }
        return response()->json($product);
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
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }
        $data = $request->all();
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe et n'est pas une URL
            if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
                $oldPath = str_replace('/storage/', '', $product->image);
                \Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        }
        $product->update($data);
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }
        // Supprimer l'image associée si ce n'est pas une URL
        if ($product->image && !filter_var($product->image, FILTER_VALIDATE_URL)) {
            $oldPath = str_replace('/storage/', '', $product->image);
            \Storage::disk('public')->delete($oldPath);
        }
        $product->delete();
        return response()->json(['message' => 'Produit supprimé']);
    }

    /**
     * Afficher le catalogue avec recherche
     */
    public function catalogue(Request $request)
    {
        $query = Product::with('category');
        
        // Recherche par mots-clés
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filtrage par catégorie
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('catalogue', compact('products', 'categories'));
    }
}
