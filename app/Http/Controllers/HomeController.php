<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produits = Product::with('category')->orderBy('created_at', 'desc')->limit(8)->get();
        $categories = Category::all();
        
        return view('accueil', compact('produits', 'categories'));
    }
} 