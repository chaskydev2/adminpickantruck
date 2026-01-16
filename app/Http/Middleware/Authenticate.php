<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Authenticate
{
    public function handle($request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('No autorizado', 401);
            }
            
            // Guardar la URL actual para redirigir después del login
            if (!$request->is('login')) {
                session(['url.intended' => $request->fullUrl()]);
            }
            
            return redirect()->route('login')
                ->with('error', 'Por favor inicia sesión para continuar');
        }

        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Verificar si el administrador tiene acceso a la ruta solicitada
        $routeName = $request->route() ? $request->route()->getName() : null;
        $adminRole = $user->role;

        // Aquí puedes agregar lógica de verificación de roles si es necesario
        // Por ejemplo, restringir ciertas rutas según el rol

        // Compartir los datos del usuario con todas las vistas
        view()->share('currentAdmin', [
            'id' => $user->id,
            'name' => $user->name,
            'role' => $user->role
        ]);

        return $next($request);
    }
}
