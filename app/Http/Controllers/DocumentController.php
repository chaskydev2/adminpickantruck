<?php

namespace App\Http\Controllers;

use App\Models\RequiredDocument;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * Muestra una lista de los documentos requeridos.
     */
    public function index(): View
    {
        $documents = RequiredDocument::withCount('userDocuments')
            ->with(['userDocuments.user' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->orderBy('name')
            ->paginate(15);
            
        return view('Documents.document_list', compact('documents'));
    }

    /**
     * Muestra el formulario para crear un nuevo documento requerido.
     */
    public function create(): View
    {
        return view('Documents.document_form');
    }

    /**
     * Almacena un nuevo documento requerido en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        RequiredDocument::create($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Documento requerido creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un documento requerido.
     */
    public function edit(RequiredDocument $document): View
    {
        return view('Documents.document_form', compact('document'));
    }

    /**
     * Actualiza el documento requerido en la base de datos.
     */
    public function update(Request $request, RequiredDocument $document)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $document->update($validated);

        return redirect()->route('documents.index')
            ->with('success', 'Documento actualizado exitosamente.');
    }

    /**
     * Elimina un documento requerido de la base de datos.
     */
    public function destroy(RequiredDocument $document)
    {
        // Verificar si hay documentos de usuario asociados
        if ($document->userDocuments()->exists()) {
            return back()->with('error', 'No se puede eliminar el documento porque tiene registros asociados.');
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Documento eliminado exitosamente.');
    }
}
