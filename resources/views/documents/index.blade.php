<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Documentos Requeridos') }}
            </h2>
            <button type="button" class="btn btn-primary" id="createDocumentBtn">
                <i class="fas fa-plus-circle me-2"></i>Nuevo Documento
            </button>
        </div>
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
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->name }}</td>
                                    <td>{{ Str::limit($document->description, 50) }}</td>
                                    <td>
                                        <span class="badge {{ $document->active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $document->active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-doc-btn" data-document-id="{{ $document->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-doc-btn" data-document-id="{{ $document->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de Creación -->
    <div class="modal fade" id="createDocumentModal" tabindex="-1" aria-labelledby="createDocumentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('documents.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDocumentModalLabel">Nuevo Documento Requerido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas Internas</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="active" name="active" value="1" checked>
                            <label class="form-check-label" for="active">Activo</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Documento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($documents as $document)
    <!-- Modal de Edición para cada documento -->
    <div class="modal fade" id="editDocumentModal{{ $document->id }}" tabindex="-1" aria-labelledby="editDocumentModalLabel{{ $document->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('documents.update', $document) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDocumentModalLabel{{ $document->id }}">Editar Documento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name{{ $document->id }}" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name{{ $document->id }}" name="name" value="{{ $document->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="description{{ $document->id }}" class="form-label">Descripción</label>
                            <textarea class="form-control" id="description{{ $document->id }}" name="description" rows="3">{{ $document->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes{{ $document->id }}" class="form-label">Notas Internas</label>
                            <textarea class="form-control" id="notes{{ $document->id }}" name="notes" rows="2">{{ $document->notes }}</textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="active{{ $document->id }}" name="active" value="1" {{ $document->active ? 'checked' : '' }}>
                            <label class="form-check-label" for="active{{ $document->id }}">Activo</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminación para cada documento -->
    <div class="modal fade" id="deleteDocumentModal{{ $document->id }}" tabindex="-1" aria-labelledby="deleteDocumentModalLabel{{ $document->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('documents.destroy', $document) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteDocumentModalLabel{{ $document->id }}">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar el documento <strong>{{ $document->name }}</strong>?</p>
                        <p class="text-danger">Esta acción no se puede deshacer y podría afectar a los usuarios que ya han subido este tipo de documento.</p>
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

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/document-modal-fix.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('js/document-modal-fix.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Botón para crear documentos
            const createBtn = document.getElementById('createDocumentBtn');
            if (createBtn) {
                createBtn.addEventListener('click', function() {
                    try {
                        const modal = document.getElementById('createDocumentModal');
                        if (modal) {
                            const modalInstance = bootstrap.Modal.getInstance(modal) || 
                                                new bootstrap.Modal(modal);
                            modalInstance.show();
                        }
                    } catch(e) {
                        console.error('Error al mostrar modal de creación:', e);
                    }
                });
            }
            
            // Botones para editar documentos
            document.querySelectorAll('.edit-doc-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    try {
                        const documentId = this.getAttribute('data-document-id');
                        const modal = document.getElementById(`editDocumentModal${documentId}`);
                        if (modal) {
                            const modalInstance = bootstrap.Modal.getInstance(modal) || 
                                                new bootstrap.Modal(modal);
                            modalInstance.show();
                        }
                    } catch(e) {
                        console.error('Error al mostrar modal de edición:', e);
                    }
                });
            });
            
            // Botones para eliminar documentos
            document.querySelectorAll('.delete-doc-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    try {
                        const documentId = this.getAttribute('data-document-id');
                        const modal = document.getElementById(`deleteDocumentModal${documentId}`);
                        if (modal) {
                            const modalInstance = bootstrap.Modal.getInstance(modal) || 
                                                new bootstrap.Modal(modal);
                            modalInstance.show();
                        }
                    } catch(e) {
                        console.error('Error al mostrar modal de eliminación:', e);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
