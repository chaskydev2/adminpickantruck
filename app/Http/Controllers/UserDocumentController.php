<?php

namespace App\Http\Controllers;

use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentStatusChanged;

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
            // Cuando el admin marque 'rechazado' el motivo (comments) deberá ser obligatorio y al menos 5 caracteres
            'comments' => 'required_if:status,rechazado|nullable|string|min:5',
        ]);
        $originalStatus = $document->status;

        $document->update($validated);
        $document->load('user');

        // Si cambió el estado y es aprobado o rechazado, mandar correo al usuario
        if ($originalStatus !== $document->status && in_array($document->status, ['aprobado', 'rechazado'])) {
            try {
                if ($document->user && filter_var($document->user->email, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($document->user->email)->send(new DocumentStatusChanged($document));
                }
            } catch (\Throwable $e) {
                // Registrar pero no fallar la petición
                logger()->error('Error enviando correo de documento: ' . $e->getMessage());
            }
        }
        if ($request->wantsJson() || $request->ajax()) {
            // Preparar datos simples y formateados para que el cliente actualice el DOM de forma determinista
            $doc = $document->toArray();
            $doc['created_at_formatted'] = $document->created_at ? $document->created_at->format('d/m/Y H:i') : null;
            $doc['user_name'] = $document->user->name ?? null;
            $doc['user_email'] = $document->user->email ?? null;
            $doc['required_document_name'] = $document->requiredDocument->name ?? null;
            $doc['status_label'] = ucfirst($document->status);
            $doc['status_class'] = ($document->status === 'aprobado') ? 'bg-success' : (($document->status === 'rechazado') ? 'bg-danger' : 'bg-warning');

            return response()->json([
                'success' => true,
                'message' => 'Documento actualizado exitosamente.',
                'document' => $doc
            ]);
        }

        return redirect()->route('user-documents.index')
            ->with('success', 'Documento actualizado exitosamente.');
    }

    /**
     * Muestra los detalles de un documento específico.
     */
    public function show(UserDocument $document): View|\Illuminate\Http\JsonResponse
    {
        $document->load(['user', 'requiredDocument']);
        
        // Si es una petición AJAX, devolver JSON con document_url explícito
        if (request()->ajax() || request()->wantsJson()) {
            $doc = $document->toArray();
            // Asegurarnos de que document_url esté presente usando el accessor del modelo
            $doc['document_url'] = $document->document_url;
            $doc['created_at_formatted'] = $document->created_at ? $document->created_at->format('d/m/Y H:i') : null;
            $doc['user_name'] = $document->user->name ?? null;
            $doc['user_email'] = $document->user->email ?? null;
            $doc['required_document_name'] = $document->requiredDocument->name ?? null;
            
            return response()->json([
                'success' => true,
                'document' => $doc
            ]);
        }
        
        return view('user_documents.show', compact('document'));
    }

    /**
     * Muestra una vista previa del documento (proxy) para evitar descargas forzadas.
     */
    public function preview(UserDocument $document)
    {
        $url = $document->document_url;
        
        if (!$url) {
            abort(404, 'URL no encontrada');
        }

        try {
            // Obtener el contenido del archivo remoto
            // Usamos context stream para ignorar errores SSL si es necesario o timeouts
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false, 
                    "verify_peer_name" => false,
                ),
            );
            $content = file_get_contents($url, false, stream_context_create($arrContextOptions));
            
            if ($content === false) {
                 abort(404, 'No se pudo leer el documento remoto.');
            }
    
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $mime = match(strtolower($extension)) {
                'pdf' => 'application/pdf',
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'webp' => 'image/webp',
                default => 'application/octet-stream'
            };
    
            return response($content)
                ->header('Content-Type', $mime)
                ->header('Content-Disposition', 'inline; filename="' . basename($document->file_path) . '"');
    
        } catch (\Exception $e) {
            abort(404, 'Error al procesar el documento: ' . $e->getMessage());
        }
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
