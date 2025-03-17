<?php

namespace App\Http\Controllers;

use App\Models\RequiredDocument;
use Illuminate\Http\Request;

class RequiredDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = RequiredDocument::all();
        return view('documents.index', compact('documents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'active' => 'boolean'
        ]);

        // Establecer active como false si no está presente en la solicitud
        if (!isset($validated['active'])) {
            $validated['active'] = false;
        }

        RequiredDocument::create($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Documento requerido creado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequiredDocument $document)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'active' => 'boolean'
        ]);

        // Establecer active como false si no está presente en la solicitud
        if (!isset($validated['active'])) {
            $validated['active'] = false;
        }

        $document->update($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Documento requerido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequiredDocument $document)
    {
        // Verificar si hay documentos de usuario asociados
        if ($document->userDocuments()->exists()) {
            return redirect()->route('documents.index')
                ->with('error', 'No se puede eliminar este documento porque hay usuarios que lo han cargado.');
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Documento requerido eliminado exitosamente.');
    }
}
