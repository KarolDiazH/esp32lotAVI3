<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Mostrar la vista del login
    public function showLogin()
    {
        // Si ya inició sesión, lo manda directo al panel
        if (Auth::check()) {
            return redirect()->route('granja.inicio');
        }
        return view('login');
    }

    // 2. Procesar el formulario de login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirige al inicio (dashboard)
            return redirect()->intended(route('granja.inicio')); 
        }

        return back()->with('error', 'Las credenciales no coinciden con nuestros registros.')->onlyInput('email');
    }

    // 3. Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}