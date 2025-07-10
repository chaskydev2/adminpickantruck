<?php

namespace App\Http\Controllers;

use App\Models\OfertaRuta;
use App\Models\TruckType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $perPage = $request->input('per_page', 10);
        
        $rutas = OfertaRuta::with(['user', 'truckType'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
            
        return view('rutas.ruta_list', compact('rutas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $truckTypes = TruckType::where('active', true)->get();
        return view('rutas.create', compact('truckTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_camion' => 'required|exists:truck_types,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'capacidad' => 'required|numeric|min:0',
            'precio_referencial' => 'required|numeric|min:0',
        ]);

        // Agregar el ID del usuario autenticado
        $validated['user_id'] = auth()->id();

        OfertaRuta::create($validated);

        return redirect()->route('rutas.index')
            ->with('success', 'Oferta de ruta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfertaRuta $ruta): View
    {
        $ruta->load(['user', 'truckType', 'bids']);
        return view('rutas.ruta_details', compact('ruta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfertaRuta $ruta): View
    {
        $truckTypes = TruckType::where('active', true)->get();
        return view('rutas.edit', compact('ruta', 'truckTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfertaRuta $ruta): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_camion' => 'required|exists:truck_types,id',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'capacidad' => 'required|numeric|min:0',
            'precio_referencial' => 'required|numeric|min:0',
        ]);

        $ruta->update($validated);

        return redirect()->route('rutas.show', $ruta)
            ->with('success', 'Oferta de ruta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfertaRuta $ruta): RedirectResponse
    {
        $ruta->delete();
        
        return redirect()->route('rutas.index')
            ->with('success', 'Oferta de ruta eliminada exitosamente.');
    }
}
