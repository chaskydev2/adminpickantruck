<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDocument;
use App\Models\RequiredDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserVerified;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // Simplificamos la consulta, ya no necesitamos eager loading
        return view('users', compact('users'));
    }

    public function checkPendingDocuments(User $user)
    {
        $requiredDocuments = RequiredDocument::where('active', true)->get();
        $missingDocuments = [];
        $pendingDocuments = [];
        $rejectedDocuments = [];

        foreach ($requiredDocuments as $required) {
            // Corregimos la consulta para usar required_document_id
            $document = $user->documents()
                           ->where('required_document_id', $required->id)
                           ->first();

            if (!$document) {
                $missingDocuments[] = $required->name;
            } elseif ($document->status === 'pendiente') {
                $pendingDocuments[] = $required->name;
            } elseif ($document->status === 'rechazado') {
                $rejectedDocuments[] = $required->name;
            }
        }
        
        return response()->json([
            'hasPendingDocuments' => (!empty($missingDocuments) || !empty($pendingDocuments) || !empty($rejectedDocuments)),
            'missingDocuments' => $missingDocuments,
            'pendingDocuments' => $pendingDocuments,
            'rejectedDocuments' => $rejectedDocuments,
            'missingCount' => count($missingDocuments),
            'pendingCount' => count($pendingDocuments),
            'rejectedCount' => count($rejectedDocuments)
        ]);
    }

    public function update(Request $request, User $user)
    {
        if ($request->action == 'toggle') {
            if (!$user->email_verified_at) {
                $requiredDocuments = RequiredDocument::where('active', true)->get();
                $documentStatus = $this->checkDocumentStatus($user, $requiredDocuments);

                if (!$documentStatus['allApproved']) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'title' => '¡No se puede verificar al usuario!',
                            'modalContent' => [
                                'faltantes' => $documentStatus['missing'],
                                'pendientes' => $documentStatus['pending'],
                                'rechazados' => $documentStatus['rejected']
                            ]
                        ], 422);
                    }
                    return back()->with('error', 'El usuario tiene documentos pendientes.');
                }

                $user->email_verified_at = now();
                $user->verified = true;
                $user->save();
                
                // Enviar email de verificación
                try {
                    \Mail::to($user->email)->send(new \App\Mail\UserVerified($user, true));
                } catch (\Exception $e) {
                    \Log::error('Error al enviar email de verificación: ' . $e->getMessage());
                }
            } else {
                $user->email_verified_at = null;
                $user->verified = false;
                $user->save();
                
                // Enviar email de desverificación
                try {
                    \Mail::to($user->email)->send(new \App\Mail\UserVerified($user, false));
                } catch (\Exception $e) {
                    \Log::error('Error al enviar email de desverificación: ' . $e->getMessage());
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $user->email_verified_at ? 'Usuario verificado exitosamente' : 'Usuario desverificado exitosamente'
                ]);
            }
            return back()->with('success', 'Estado del usuario actualizado exitosamente');
        } elseif ($request->action == 'delete') {
            $user->delete();
            return redirect()->route('users.index')
                           ->with('success', 'Usuario eliminado exitosamente');
        }

        return redirect()->route('users.index');
    }

    private function checkDocumentStatus(User $user, $requiredDocuments)
    {
        $missing = [];
        $pending = [];
        $rejected = [];
        $allApproved = true;

        foreach ($requiredDocuments as $doc) {
            // Corregimos la consulta para usar required_document_id
            $userDoc = $user->documents()
                           ->where('required_document_id', $doc->id)
                           ->first();

            if (!$userDoc) {
                $missing[] = $doc->name;
                $allApproved = false;
            } elseif ($userDoc->status === 'pendiente') {
                $pending[] = $doc->name;
                $allApproved = false;
            } elseif ($userDoc->status === 'rechazado') {
                $rejected[] = $doc->name;
                $allApproved = false;
            }
        }

        return [
            'allApproved' => $allApproved,
            'missing' => $missing,
            'pending' => $pending,
            'rejected' => $rejected
        ];
    }

    public function showDocument($id)
    {
        $userDocument = UserDocument::findOrFail($id);
        
        // Verificamos si la ruta comienza con 'http' o 'https'
        if (filter_var($userDocument->file_path, FILTER_VALIDATE_URL)) {
            return redirect($userDocument->file_path);
        }
        
        // Construimos la URL pública para el documento
        $baseUrl = config('app.documents_url', 'https://app.pickntruck.com/storage');
        $documentUrl = $baseUrl . '/' . $userDocument->file_path;
        
        // Redirigimos al usuario a la URL del documento
        return redirect($documentUrl);
    }

    public function updateDocumentStatus(Request $request, $documentId)
    {
        $userDocument = UserDocument::findOrFail($documentId);
        $validated = $request->validate([
            'status' => 'required|in:pendiente,aprobado,rechazado',
            'comments' => 'nullable|string'
        ]);

        $previousStatus = $userDocument->status;
        $userDocument->update($validated);
        
        // Si el estado cambió, enviar notificación por email
        if ($previousStatus !== $validated['status']) {
            try {
                \Mail::to($userDocument->user->email)
                    ->send(new \App\Mail\DocumentStatusUpdated($userDocument));
            } catch (\Exception $e) {
                \Log::error('Error al enviar email de actualización de documento: ' . $e->getMessage());
            }
        }
        
        // Devolver respuesta JSON para peticiones AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado del documento actualizado exitosamente',
                'document' => [
                    'id' => $userDocument->id,
                    'status' => $userDocument->status,
                    'comments' => $userDocument->comments
                ]
            ]);
        }
        
        return redirect()->back()->with('success', 'Estado del documento actualizado exitosamente');
    }

    /**
     * Verificar un usuario
     */
    public function verify(Request $request, User $user)
    {
        // Si el usuario ya está verificado, no hacer nada
        if ($user->email_verified_at) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario ya verificado'
            ]);
        }

        // Si no se está forzando la verificación, verificar documentos
        if (!$request->input('force', false)) {
            $requiredDocuments = RequiredDocument::where('active', true)->get();
            $documentStatus = $this->checkDocumentStatus($user, $requiredDocuments);

            if (!$documentStatus['allApproved']) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario tiene documentos pendientes o faltantes',
                    'documentStatus' => $documentStatus
                ], 422);
            }
        }

        // Verificar al usuario
        $user->email_verified_at = now();
        $user->verified = true;
        $user->save();
        
        // Enviar email de verificación
        try {
            Mail::to($user->email)->send(new UserVerified($user, true));
        } catch (\Exception $e) {
            \Log::error('Error al enviar email de verificación: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario verificado exitosamente'
        ]);
    }

    /**
     * Desverificar un usuario
     */
    public function unverify(User $user)
    {
        $user->email_verified_at = null;
        $user->verified = false;
        $user->save();
        
        // Enviar email de desverificación
        try {
            Mail::to($user->email)->send(new UserVerified($user, false));
        } catch (\Exception $e) {
            \Log::error('Error al enviar email de desverificación: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario desverificado exitosamente'
        ]);
    }

    /**
     * Obtener detalles del usuario incluyendo sus documentos
     */
    public function details(User $user)
    {
        // Cargar los documentos del usuario con información adicional
        $documents = $user->documents()
            ->join('required_documents', 'user_documents.required_document_id', '=', 'required_documents.id')
            ->select([
                'user_documents.id',
                'user_documents.status',
                'user_documents.file_path',
                'user_documents.comments',
                'required_documents.name',
                'required_documents.description as document_type'
            ])
            ->get();
        
        // Formatear la fecha de creación para mostrarla en el frontend
        $formattedUser = $user->toArray();
        $formattedUser['formatted_date'] = $user->created_at->format('d/m/Y H:i');
        
        return response()->json([
            'user' => $formattedUser,
            'documents' => $documents
        ]);
    }
}
