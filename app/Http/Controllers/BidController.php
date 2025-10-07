<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Ruta;
use App\Models\Carga;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    /**
     * Muestra una lista de todos los bids.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $estado = $request->input('estado');
        
        $query = Bid::with(['user', 'bideable']);
        
        // Aplicar filtro por estado si se especificó
        if ($estado) {
            if (in_array($estado, ['pendiente', 'aceptado', 'rechazado', 'cancelado'])) {
                $query->where('estado', $estado);
            } elseif (in_array($estado, ['completado', 'terminado'])) {
                // Manejar tanto 'completado' como 'terminado' como equivalentes
                $query->whereIn('estado', ['completado', 'terminado']);
            }
        }
        
        $bids = $query->latest()
                     ->paginate($perPage)
                     ->withQueryString();

        return view('bids.bid_list', compact('bids', 'estado'));
    }

    /**
     * Muestra el formulario para crear un nuevo bid.
     */
    public function create($type, $id)
    {
        $model = $type === 'ruta' ? Ruta::findOrFail($id) : Carga::findOrFail($id);
        return view('bids.create', compact('model', 'type'));
    }

    /**
     * Almacena un nuevo bid en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bideable_id' => 'required|integer',
            'bideable_type' => 'required|in:App\\Models\\Ruta,App\\Models\\Carga',
            'monto' => 'required|numeric|min:0',
            'comentario' => 'nullable|string|max:1000',
        ]);

        $bid = new Bid();
        $bid->user_id = Auth::id();
        $bid->bideable_id = $validated['bideable_id'];
        $bid->bideable_type = $validated['bideable_type'];
        $bid->monto = $validated['monto'];
        $bid->comentario = $validated['comentario'] ?? null;
        $bid->fecha_hora = now();
        $bid->estado = 'pendiente';
        $bid->save();

        return redirect()->route('bids.show', $bid->id)
                        ->with('success', 'Oferta enviada correctamente');
    }

    /**
     * Muestra los detalles de un bid específico.
     */
    public function show($id)
    {
        $bid = Bid::with(['user', 'bideable', 'chat.messages.user'])->findOrFail($id);
        
        // Obtener o crear el chat para esta oferta
        $chat = $bid->chat ?? new Chat([
            'bid_id' => $bid->id,
            'user_a_id' => $bid->user_id,
            'user_b_id' => $bid->bideable->user_id
        ]);
        
        if (!$bid->chat) {
            $chat->save();
            $bid->refresh();
        }
        
        return view('bids.bid_details', compact('bid', 'chat'));
    }

    /**
     * Actualiza el estado de un bid (aceptar/rechazar).
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'estado' => 'required|in:aceptado,rechazado,cancelado',
        ]);

        $bid = Bid::findOrFail($id);
        $bid->estado = $validated['estado'];
        $bid->save();

        return back()->with('success', 'Estado de la oferta actualizado correctamente');
    }

    /**
     * Confirma la aceptación de un bid por parte de un usuario.
     */
    public function confirmarAceptacion($id, $tipoUsuario)
    {
        $bid = Bid::findOrFail($id);
        
        if ($tipoUsuario === 'a') {
            $bid->confirmacion_usuario_a = true;
        } else {
            $bid->confirmacion_usuario_b = true;
        }
        
        $bid->save();

        return back()->with('success', 'Confirmación registrada correctamente');
    }
}
