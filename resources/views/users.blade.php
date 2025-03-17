<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Estado</th>
                                    <th>Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->email_verified_at ? 'bg-success' : 'bg-warning' }} verification-status">
                                            {{ $user->email_verified_at ? 'Verificado' : 'No Verificado' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#userDetailModal" data-user-id="{{ $user->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <button class="btn btn-sm btn-success btn-verify-user {{ $user->email_verified_at ? 'd-none' : '' }}" 
                                                data-user-id="{{ $user->id }}" title="Verificar Usuario">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            
                                            <form action="{{ route('users.update', $user) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="action" value="toggle">
                                                <button type="submit" class="btn btn-sm btn-warning btn-unverify-user {{ $user->email_verified_at ? '' : 'd-none' }}" 
                                                    title="Desverificar Usuario">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Modal de Eliminación para cada usuario -->
                                <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('users.update', $user) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="action" value="delete">
                                                
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">Confirmar Eliminación</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas eliminar al usuario <strong>{{ $user->name }}</strong>?</p>
                                                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Detalles de Usuario -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">Detalles del Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Información Personal</h6>
                            <p><strong>Nombre:</strong> <span id="userName"></span></p>
                            <p><strong>Email:</strong> <span id="userEmail"></span></p>
                            <p><strong>Teléfono:</strong> <span id="userPhone"></span></p>
                            <p><strong>Estado:</strong> <span id="userStatus"></span></p>
                            <p><strong>Fecha de Registro:</strong> <span id="userCreatedAt"></span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Documentos</h6>
                            <div id="userDocuments">
                                <p class="text-muted">Cargando documentos...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Documentos Pendientes -->
    <div class="modal fade" id="pendingDocumentsModal" tabindex="-1" aria-labelledby="pendingDocumentsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="pendingDocumentsModalLabel">Documentos Pendientes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <p>El usuario tiene documentos pendientes de revisión o aprobación.</p>
                        <p>No se recomienda verificar a usuarios con documentación incompleta o pendiente.</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Documentos Faltantes:</h6>
                        <ul id="missingDocumentsList" class="list-group mb-3">
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Documentos Pendientes:</h6>
                        <ul id="pendingDocumentsList" class="list-group mb-3">
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Documentos Rechazados:</h6>
                        <ul id="rejectedDocumentsList" class="list-group mb-3">
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-warning" id="forceVerifyBtn">Verificar de todas formas</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Éxito -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Operación Exitosa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>La operación se completó exitosamente.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Error -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Error</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Ocurrió un error durante la operación.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/users-modal-fix.js') }}"></script>
    <script src="{{ asset('js/users-detail-modal.js') }}"></script>
    <script src="{{ asset('js/document-update.js') }}"></script>
    <script>
        // Si hay algún problema específico con CSRF, asegurémonos de que el token esté disponible
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar token CSRF
            const metaToken = document.querySelector('meta[name="csrf-token"]);
            if (!metaToken) {
                // Crear si no existe
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = "{{ csrf_token() }}";
                document.head.appendChild(meta);
            }
        });
    </script>
    @endpush
</x-app-layout>
