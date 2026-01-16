<?php

namespace App\Http\Controllers;

use App\Models\OfertaCarga;
use App\Models\CargoType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CargaController extends Controller
{
    /**
     * Muestra una lista de todas las ofertas de carga.
     */
    public function index(Request $request): View
    {
        $perPage = $request->input('per_page', 10);
        
        $cargas = OfertaCarga::with(['user', 'cargoType'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
            
        return view('cargas.carga_list', compact('cargas'));
    }

    /**
     * Muestra el formulario para crear una nueva oferta de carga.
     */
    public function create(): View
    {
        $tiposCarga = CargoType::active()->get();
        return view('cargas.create', compact('tiposCarga'));
    }

    /**
     * Almacena una nueva oferta de carga en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_carga' => 'required|exists:cargo_types,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'peso' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'presupuesto' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        OfertaCarga::create($validated);

        return redirect()->route('cargas.index')
            ->with('success', 'Oferta de carga creada exitosamente.');
    }

    /**
     * Muestra los detalles de una oferta de carga específica.
     */
    public function show(OfertaCarga $carga): View
    {
        $carga->load(['user', 'cargoType']);
        return view('cargas.carga_details', compact('carga'));
    }

    /**
     * Muestra el formulario para editar una oferta de carga.
     */
    public function edit(OfertaCarga $carga): View
    {
        $tiposCarga = CargoType::active()->get();
        return view('cargas.edit', compact('carga', 'tiposCarga'));
    }

    /**
     * Actualiza la oferta de carga en la base de datos.
     */
    public function update(Request $request, OfertaCarga $carga): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_carga' => 'required|exists:cargo_types,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'peso' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'presupuesto' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $carga->update($validated);

        return redirect()->route('cargas.show', $carga)
            ->with('success', 'Oferta de carga actualizada exitosamente.');
    }

    /**
     * Elimina una oferta de carga de la base de datos.
     */
    public function destroy(OfertaCarga $carga): JsonResponse
    {
        try {
            $carga->delete();
            return response()->json([
                'success' => true,
                'message' => 'Oferta de carga eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la oferta de carga: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtiene las ofertas de carga de un usuario específico.
     */
    public function porUsuario($userId): View
    {
        $ofertas = OfertaCarga::with('cargoType')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'carga_page');
            
        return view('cargas.por_usuario', compact('ofertas'));
    }
}
