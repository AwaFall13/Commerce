<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
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
    }

    // Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Identifiants incorrects']);
        }

        session(['user_id' => $user->id]);
        return redirect('/')->with('success', 'Connexion réussie !');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        session()->forget('user_id');
        return redirect('/')->with('success', 'Déconnexion réussie !');
    }
}
