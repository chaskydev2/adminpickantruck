<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Constructor del controlador
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Muestra el formulario de inicio de sesión
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Maneja el intento de inicio de sesión
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Establecer datos adicionales en la sesión si es necesario
            $admin = Auth::user();
            Session::put([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_role' => $admin->role
            ]);
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
