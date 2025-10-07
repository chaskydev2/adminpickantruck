<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bid;
use App\Models\Carga;
use App\Models\Ruta;
use App\Models\Document;
use App\Models\OfertaCarga;
use App\Models\OfertaRuta;
use App\Models\RequiredDocument;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Buscar usuarios por ID, email o nombre
     */
    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255',
        ]);

        $search = trim($request->input('query'));
        
        try {
            $users = User::query()
                ->when($search, function($query) use ($search) {
                    // Si el término de búsqueda empieza con #, buscar por ID
                    if (str_starts_with($search, '#')) {
                        $id = ltrim($search, '#');
                        return $query->where('id', $id);
                    }
                    
                    // Buscar por email o nombre
                    return $query->where('email', 'like', "%{$search}%")
                               ->orWhere('name', 'like', "%{$search}%");
                })
                ->select(['id', 'name', 'email'])
                ->limit(10)
                ->get()
                ->map(function($user) {
                    // Usar una imagen por defecto si no hay foto de perfil
                    $profilePhotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF';
                    
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'profile_photo_url' => $profilePhotoUrl,
                        'show_url' => route('users.show', $user),
                    ];
                });
                
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en la búsqueda de usuarios: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la búsqueda: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // Método para manejar solicitudes de pre-vuelo CORS
    public function options()
    {
        return response()->json(['success' => true]);
    }

    /**
     * Busca cargas según los criterios proporcionados
     */
    public function searchCargas(Request $request)
    {
        try {
            $search = trim($request->input('query', ''));
            
            $cargas = \App\Models\OfertaCarga::with(['user', 'cargoType'])
                ->when($search, function($query) use ($search) {
                    // Si el término de búsqueda empieza con #, buscar por ID
                    if (str_starts_with($search, '#')) {
                        $id = ltrim($search, '#');
                        return $query->where('id', $id);
                    }
                    
                    // Búsqueda por múltiples campos
                    return $query->where('id', 'like', "%{$search}%")
                        ->orWhere('origen', 'like', "%{$search}%")
                        ->orWhere('destino', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('cargoType', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($carga) {
                    return [
                        'id' => $carga->id,
                        'user_name' => $carga->user->name,
                        'user_id' => $carga->user_id,
                        'tipo_carga' => $carga->cargoType->name,
                        'origen' => $carga->origen,
                        'destino' => $carga->destino,
                        'peso' => $carga->peso,
                        'fecha_carga' => $carga->fecha_inicio,
                        'presupuesto' => $carga->presupuesto,
                        'show_url' => route('cargas.show', $carga->id),
                        'user_url' => route('users.show', $carga->user_id)
                    ];
                });
                
            return response()->json([
                'success' => true,
                'data' => $cargas
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en la búsqueda de cargas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la búsqueda: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    
    /**
     * Busca rutas según los criterios proporcionados
     */
    public function searchRutas(Request $request)
    {
        try {
            $search = trim($request->input('query', ''));
            
            $rutas = \App\Models\OfertaRuta::with(['user', 'truckType'])
                ->when($search, function($query) use ($search) {
                    // Si el término de búsqueda empieza con #, buscar por ID
                    if (str_starts_with($search, '#')) {
                        $id = ltrim($search, '#');
                        return $query->where('id', $id);
                    }
                    
                    // Búsqueda por múltiples campos
                    $query->where(function($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                          ->orWhere('origen', 'like', "%{$search}%")
                          ->orWhere('destino', 'like', "%{$search}%")
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                          })
                          ->orWhereHas('truckType', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                    });
                    
                    return $query;
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($ruta) {
                    return [
                        'id' => $ruta->id,
                        'user_name' => $ruta->user ? $ruta->user->name : 'Usuario no disponible',
                        'user_id' => $ruta->user_id,
                        'tipo_camion' => $ruta->truckType ? $ruta->truckType->name : 'No especificado',
                        'origen' => $ruta->origen,
                        'destino' => $ruta->destino,
                        'fecha_salida' => $ruta->fecha_inicio,
                        'capacidad' => $ruta->capacidad,
                        'precio_referencia' => $ruta->precio_referencial,
                        'pujas_count' => $ruta->pujas_count ?? 0,
                        'show_url' => route('rutas.show', $ruta->id),
                        'user_url' => $ruta->user_id ? route('users.show', $ruta->user_id) : '#'
                    ];
                });
                
            return response()->json([
                'success' => true,
                'data' => $rutas
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en la búsqueda de rutas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la búsqueda: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    
    /**
     * Busca documentos según los criterios proporcionados
     */
    public function searchDocuments(Request $request)
    {
        try {
            $search = trim($request->input('query', ''));
            
            $documents = \App\Models\RequiredDocument::query()
                ->when($search, function($query) use ($search) {
                    // Si el término de búsqueda empieza con #, buscar por ID
                    if (str_starts_with($search, '#')) {
                        $id = ltrim($search, '#');
                        return $query->where('id', $id);
                    }
                    
                    // Búsqueda por múltiples campos
                    return $query->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('active', function($q) use ($search) {
                            $status = strtolower($search);
                            if (str_contains($status, 'activo') || str_contains($status, 'habilitado')) {
                                return $q->where('active', true);
                            } elseif (str_contains($status, 'inactivo') || str_contains($status, 'deshabilitado')) {
                                return $q->where('active', false);
                            }
                            return $q->where('id', '>', 0); // No filtrar por estado si no coincide
                        });
                })
                ->orderBy('name')
                ->limit(10)
                ->get()
                ->map(function($document) {
                    return [
                        'id' => $document->id,
                        'name' => $document->name,
                        'description' => $document->description,
                        'active' => $document->active,
                        'edit_url' => route('documents.edit', $document->id),
                        'show_url' => route('documents.show', $document->id),
                        'documents_count' => $document->userDocuments()->count(),
                        'created_at' => $document->created_at,
                        'updated_at' => $document->updated_at
                    ];
                });
                
            return response()->json([
                'success' => true,
                'data' => $documents
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en la búsqueda de documentos: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la búsqueda: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    
    /**
     * Busca ofertas según los criterios proporcionados
     */
    public function searchBids(Request $request)
    {
        try {
            $search = trim($request->input('query', ''));
            
            $bids = \App\Models\Bid::with(['user', 'bideable'])
                ->when($search, function($query) use ($search) {
                    // Si el término de búsqueda empieza con #, buscar por ID
                    if (str_starts_with($search, '#')) {
                        $id = ltrim($search, '#');
                        return $query->where('id', $id);
                    }
                    
                    // Búsqueda por múltiples campos
                    return $query->where(function($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                          ->orWhere('monto', 'like', "%{$search}%")
                          ->orWhereHas('user', function($subQuery) use ($search) {
                              $subQuery->where('name', 'like', "%{$search}%")
                                      ->orWhere('email', 'like', "%{$search}%");
                          });
                    })->orWhere(function($q) use ($search) {
                        $status = strtolower($search);
                        if (str_contains($status, 'acept') || str_contains($status, 'aprob')) {
                            $q->where('estado', 'aceptado');
                        } elseif (str_contains($status, 'rechaz') || str_contains($status, 'rechazado')) {
                            $q->where('estado', 'rechazado');
                        } elseif (str_contains($status, 'pendiente') || str_contains($status, 'espera')) {
                            $q->where('estado', 'pendiente');
                        } elseif (str_contains($status, 'cancel') || str_contains($status, 'anul')) {
                            $q->where('estado', 'cancelado');
                        } else {
                            $q->where('estado', 'like', "%{$search}%");
                        }
                    });
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($bid) {
                    $propietario = $bid->propietario();
                    
                    return [
                        'id' => $bid->id,
                        'postor_name' => $bid->user ? $bid->user->name : 'N/A',
                        'postor_url' => $bid->user ? route('users.show', $bid->user) : '#',
                        'propietario_name' => $propietario ? $propietario->name : 'N/A',
                        'propietario_url' => $propietario ? route('users.show', $propietario) : '#',
                        'tipo' => $bid->tipo_objeto,
                        'monto' => $bid->monto,
                        'fecha' => $bid->fecha_hora->format('d/m/Y'),
                        'estado' => $bid->estado,
                        'show_url' => route('bids.show', $bid->id),
                        'created_at' => $bid->created_at,
                        'updated_at' => $bid->updated_at
                    ];
                });
                
            return response()->json([
                'success' => true,
                'data' => $bids
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en la búsqueda de ofertas: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al realizar la búsqueda: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
    
    /**
     * Búsqueda global en todos los recursos
     */
    public function searchGlobal(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1|max:255',
        ]);

        $search = trim($request->input('query'));
        $results = collect();
        
        // Depuración temporal
        if ($search === '#1') {
            $testUser = User::find(1);
            dd([
                'search' => $search,
                'user_1_exists' => $testUser ? true : false,
                'request_all' => $request->all()
            ]);
        }

        // Verificar si la búsqueda es por ID (empieza con #)
        if (str_starts_with($search, '#')) {
            $id = ltrim($search, '#');
            
            // Buscar por ID exacto en todos los modelos
            $user = User::find($id);
            if ($user) {
                $results->push([
                    'type' => 'user',
                    'id' => $user->id,
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'url' => route('users.show', $user),
                    'icon' => 'fas fa-user'
                ]);
            }
            
            $carga = Carga::with(['user', 'cargoType'])->find($id);
            if ($carga) {
                $results->push([
                    'type' => 'carga',
                    'id' => $carga->id,
                    'title' => "Carga #{$carga->id}",
                    'subtitle' => "{$carga->origen} → {$carga->destino}",
                    'url' => route('cargas.show', $carga),
                    'icon' => 'fas fa-box'
                ]);
            }
            
            $ruta = Ruta::with(['user', 'truckType'])->find($id);
            if ($ruta) {
                $results->push([
                    'type' => 'ruta',
                    'id' => $ruta->id,
                    'title' => "Ruta #{$ruta->id}",
                    'subtitle' => "{$ruta->origen} → {$ruta->destino}",
                    'url' => route('rutas.show', $ruta),
                    'icon' => 'fas fa-route'
                ]);
            }
            
            $bid = Bid::with(['user', 'bideable'])->find($id);
            if ($bid) {
                $type = $bid->bideable_type === 'App\\Models\\Carga' ? 'Carga' : 'Ruta';
                $results->push([
                    'type' => 'bid',
                    'id' => $bid->id,
                    'title' => "Puja #{$bid->id}",
                    'subtitle' => "Para {$type} #" . $bid->bideable_id . " - " . ucfirst($bid->estado),
                    'url' => route('bids.show', $bid),
                    'icon' => 'fas fa-gavel'
                ]);
            }
            
            $document = Document::find($id);
            if ($document) {
                $results->push([
                    'type' => 'document',
                    'id' => $document->id,
                    'title' => $document->name,
                    'subtitle' => str_limit($document->description, 50),
                    'url' => route('documents.show', $document),
                    'icon' => 'fas fa-file-alt'
                ]);
            }
            
            return response()->json($results->take(10));
        }

        // Búsqueda normal por texto
        // Buscar usuarios
        $users = User::query()
            ->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(5)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user',
                    'id' => $user->id,
                    'title' => $user->name,
                    'subtitle' => $user->email,
                    'url' => route('users.show', $user),
                    'icon' => 'fas fa-user'
                ];
            });

        // Buscar cargas
        $cargas = Carga::query()
            ->where('origen', 'like', "%{$search}%")
            ->orWhere('destino', 'like', "%{$search}%")
            ->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->with(['user', 'cargoType'])
            ->limit(5)
            ->get()
            ->map(function($carga) {
                return [
                    'type' => 'carga',
                    'id' => $carga->id,
                    'title' => "Carga #{$carga->id}",
                    'subtitle' => "{$carga->origen} → {$carga->destino}",
                    'url' => route('cargas.show', $carga),
                    'icon' => 'fas fa-box'
                ];
            });

        // Buscar rutas
        $rutas = Ruta::query()
            ->where('origen', 'like', "%{$search}%")
            ->orWhere('destino', 'like', "%{$search}%")
            ->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->with(['user', 'truckType'])
            ->limit(5)
            ->get()
            ->map(function($ruta) {
                return [
                    'type' => 'ruta',
                    'id' => $ruta->id,
                    'title' => "Ruta #{$ruta->id}",
                    'subtitle' => "{$ruta->origen} → {$ruta->destino}",
                    'url' => route('rutas.show', $ruta),
                    'icon' => 'fas fa-route'
                ];
            });

        // Buscar pujas
        $bids = Bid::query()
            ->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->with(['user', 'bideable'])
            ->limit(5)
            ->get()
            ->map(function($bid) {
                $type = $bid->bideable_type === 'App\\Models\\Carga' ? 'Carga' : 'Ruta';
                return [
                    'type' => 'bid',
                    'id' => $bid->id,
                    'title' => "Puja #{$bid->id}",
                    'subtitle' => "Para {$type} #" . $bid->bideable_id . " - " . ucfirst($bid->estado),
                    'url' => route('bids.show', $bid),
                    'icon' => 'fas fa-gavel'
                ];
            });

        // Buscar documentos
        $documents = Document::query()
            ->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->limit(5)
            ->get()
            ->map(function($doc) {
                return [
                    'type' => 'document',
                    'id' => $doc->id,
                    'title' => $doc->name,
                    'subtitle' => str_limit($doc->description, 50),
                    'url' => route('documents.show', $doc),
                    'icon' => 'fas fa-file-alt'
                ];
            });

        // Combinar todos los resultados
        $results = $users
            ->merge($cargas)
            ->merge($rutas)
            ->merge($bids)
            ->merge($documents)
            ->take(10);

        return response()->json($results);
    }
}
