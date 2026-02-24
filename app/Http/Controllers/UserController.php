<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View; // Importa View para tipo hinting

class UserController extends Controller
{
    /**
     * Cambia el estado de un usuario entre Activo y Bloqueado
     */
    public function toggleStatus(User $user)
    {
        $user->estado = $user->estado === 'Activo' ? 'Bloqueado' : 'Activo';
        $user->save();

        return back()->with('success', 'Estado del usuario actualizado correctamente');
    }

    /**
    /**
     * Muestra los detalles de un usuario específico.
     */
    public function show(User $user): View
    {
        // Cargar el usuario con las relaciones necesarias
        $user->load([
            'ofertasCarga' => function($query) {
                $query->with('cargoType')
                      ->orderBy('created_at', 'desc');
            },
            'ofertasRuta' => function($query) {
                $query->with('truckType')
                      ->orderBy('fecha_inicio', 'desc');
            },
            // Los detalles del usuario ahora están en la tabla users
        ]);
            
        return view('users.user_detail_max', compact('user'));
    }
    /**
     * Muestra la lista de usuarios.
     */
    public function index()
{
    $perPage = request('per_page', 10); // Valor por defecto = 10

    $users = User::withCount('documents')
        ->with(['documents' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate($perPage)
        ->appends(['per_page' => $perPage]);

    return view('users.user_list', compact('users'));
}


    /**
     * Verificar/Desverificar un usuario
     */
    public function toggleVerification(User $user)
    {
        try {
            $nuevoEstado = !$user->verified;
            $user->verified = $nuevoEstado;
            $user->save(); 

            // Verificar si el usuario acaba de ser verificado para enviar el correo
            if ($nuevoEstado) {
                try {
                    \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\UserVerified($user));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Error al enviar correo de verificación al usuario {$user->id}: " . $e->getMessage());
                    // No detenemos la ejecución si falla el correo, pero lo registramos
                }
            }

            $message = $nuevoEstado ? 'Usuario verificado correctamente' : 'Usuario desverificado correctamente';
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_verified' => (bool)$user->verified, // Asegúrate de que sea un booleano para JS
            ]);
            
        } catch (\Exception $e) {
            // Puedes loguear el error para depuración en el servidor
            // \Log::error("Error al cambiar estado de verificación para el usuario ID {$user->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado del usuario: ' . $e->getMessage() // Mensaje más descriptivo en caso de error
            ], 500);
        }
    }
    
    /**
     * Eliminar un usuario
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            // Puedes loguear el error para depuración en el servidor
            // \Log::error("Error al eliminar usuario ID {$user->id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage() // Mensaje más descriptivo en caso de error
            ], 500);
        }
    }
}