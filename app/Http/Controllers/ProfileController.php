<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil del administrador
     */
    public function show()
    {
        $admin = Auth::guard('web')->user();
        return view('profile.show', compact('admin'));
    }

    /**
     * Muestra el formulario de edición de perfil
     */
    public function edit()
    {
        $admin = Auth::guard('web')->user();
        return view('profile.edit', compact('admin'));
    }

    /**
     * Actualiza la información del perfil
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('web')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:administrators,email,' . $admin->id],
        ]);

        $admin->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Muestra el formulario para cambiar la contraseña
     */
    public function showChangePassword()
    {
        return view('profile.change-password');
    }

    /**
     * Actualiza la contraseña
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin = Auth::guard('web')->user();
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Contraseña actualizada correctamente');
    }
}
