<?php

namespace App\Http\Controllers;

use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserDocumentController extends Controller
{
    /**
     * Muestra una lista de los documentos subidos por los usuarios.
     */
    public function index(): View
    {
        $documents = UserDocument::with(['user', 'requiredDocument'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('user_documents.index', compact('documents'));
    }

    /**
     * Muestra el formulario para editar el estado de un documento.
     */
    public function edit(UserDocument $document): View
    {
        $statusOptions = UserDocument::getStatusOptions();
        return view('user_documents.edit', compact('document', 'statusOptions'));
    }

    /**
     * Actualiza el estado de un documento.
     */
    public function update(Request $request, UserDocument $document)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(UserDocument::getStatusOptions())),
            'admin_notes' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $document->update($validated);
        $document->load('user');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Documento actualizado exitosamente.',
                'document' => $document
            ]);
        }

        return redirect()->route('user-documents.index')
            ->with('success', 'Documento actualizado exitosamente.');
    }

    /**
     * Muestra los detalles de un documento específico.
     */
    public function show(UserDocument $document): View
    {
        $document->load(['user', 'requiredDocument']);
        return view('user_documents.show', compact('document'));
    }

    /**
     * Elimina un documento.
     */
    public function destroy(UserDocument $document): RedirectResponse
    {
        // Aquí podrías agregar lógica para eliminar el archivo físico si es necesario
        // Storage::delete($document->file_path);
        
        $document->delete();

        return redirect()->route('user-documents.index')
            ->with('success', 'Documento eliminado exitosamente.');
    }
}
