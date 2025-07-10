@extends('layouts.home')

@section('title', 'Documentos Requeridos')

@push('styles')
<style>
    .document-details {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        background-color: #f8f9fa;
        transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
        transform-origin: top;
        transform: scaleY(0);
        display: table-row;
    }
    .document-details.show {
        max-height: 1000px; /* Ajusta según sea necesario */
        opacity: 1;
        transform: scaleY(1);
    }
    .document-details > td {
        padding: 0 !important;
        border-top: none;
        transform: scaleY(0);
        transform-origin: top;
        transition: transform 0.3s ease-in-out;
    }
    .document-details.show > td {
        transform: scaleY(1);
    }
    .document-details .inner-content {
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease-in-out 0.1s;
        height: 0;
        overflow: hidden;
    }
    .document-details.show .inner-content {
        opacity: 1;
        transform: translateY(0);
        height: auto;
        overflow: visible;
    }
    .document-details .inner-table {
        width: 100%;
        margin: 0;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .document-details .table-responsive {
        padding: 0 !important;
    }
    .document-details .inner-table th {
        background-color: #e9ecef;
    }
    .toggle-details {
        cursor: pointer;
        transition: all 0.2s;
    }
    .toggle-details:hover {
        transform: scale(1.05);
    }
    /* Estilos para el modal de validación */
    .modal-backdrop {
        opacity: 0.5 !important;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <h2 class="mb-0 me-4">Documentos Requeridos</h2>
    </div>
    <div class="search-container" style="width: 400px;">
        <div id="search-document-component">
            <search-document></search-document>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle document details
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                const detailsRow = document.getElementById(`details-${documentId}`);
                const isExpanding = !detailsRow.classList.contains('show');
                
                if (isExpanding) {
                    // Muestra la fila antes de la animación
                    detailsRow.classList.remove('d-none');
                    detailsRow.style.display = 'table-row';
                    // Fuerza el reflow para que la transición funcione
                    void detailsRow.offsetHeight;
                    // Aplica la clase para la animación
                    detailsRow.classList.add('show');
                } else {
                    // Inicia la animación de ocultar
                    detailsRow.classList.remove('show');
                    // Espera a que termine la animación para ocultar completamente
                    detailsRow.addEventListener('transitionend', function handler(e) {
                        if (e.propertyName === 'transform' && !detailsRow.classList.contains('show')) {
                            detailsRow.style.display = 'none';
                            detailsRow.classList.add('d-none');
                            detailsRow.removeEventListener('transitionend', handler);
                        }
                    });
                }
                
                // Rotate icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            });
        });
    });
</script>
@endpush

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Documentos subidos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>#{{ $document->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="far fa-file-alt text-primary me-2"></i>
                                    <span>{{ $document->name }}</span>
                                </div>
                            </td>
                            <td>{{ Str::limit($document->description, 50) }}</td>
                            <td>
                                @if($document->active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                @if($document->user_documents_count > 0)
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary toggle-details" 
                                            data-document-id="{{ $document->id }}">
                                        <i class="fas fa-chevron-down me-1"></i>
                                        {{ $document->user_documents_count }} subidos
                                    </button>
                                @else
                                    <span class="badge bg-secondary">Ninguno</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('documents.edit', $document) }}" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       data-bs-toggle="tooltip" 
                                       title="Editar">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger delete-document" 
                                            data-id="{{ $document->id }}"
                                            data-bs-toggle="tooltip" 
                                            title="Eliminar">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        @if($document->user_documents_count > 0)
                        <tr id="details-{{ $document->id }}" class="document-details d-none">
                            <td colspan="6">
                                <div class="p-3 inner-content">
                                    <table class="table table-sm inner-table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Usuario</th>
                                                <th>Email</th>
                                                <th>Estado</th>
                                                <th>Fecha de subida</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($document->userDocuments as $userDocument)
                                            <tr>
                                                <td>{{ $userDocument->user->name ?? 'N/A' }}</td>
                                                <td>{{ $userDocument->user->email ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                         $statusClass = [
                                                             'pendiente' => 'bg-warning',
                                                             'aprobado' => 'bg-success',
                                                             'rechazado' => 'bg-danger'
                                                         ][$userDocument->status] ?? 'bg-secondary';
                                                    @endphp
                                                    <span class="badge {{ $statusClass }}">
                                                        {{ ucfirst($userDocument->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $userDocument->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-center">
                                                    <button type="button" 
                                                            class="btn btn-sm {{ $userDocument->status === 'aprobado' ? 'btn-outline-success' : 'btn-outline-primary' }} open-validation-modal"
                                                            data-document-id="{{ $userDocument->id }}"
                                                            data-status="{{ $userDocument->status }}">
                                                        <i class="far {{ $userDocument->status === 'aprobado' ? 'fa-check-circle' : 'fa-eye' }} me-1"></i> 
                                                        {{ $userDocument->status === 'aprobado' ? 'Validado' : 'Ver' }}
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x mb-3 text-muted"></i>
                                <p class="mb-0">No hay documentos registrados</p>
                                <p class="text-muted small mt-2">
                                    <a href="{{ route('documents.create') }}" class="text-primary">
                                        <i class="fas fa-plus me-1"></i> Agregar un documento
                                    </a>
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($documents->hasPages())
            <div class="px-4 py-3">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>

<div class="position-fixed" style="bottom: 2rem; right: 2rem;">
    <a href="{{ route('documents.create') }}" class="btn btn-primary px-4 shadow" data-bs-placement="left">
        <i class="fas fa-plus me-2"></i> Agregar un documento
    </a>
</div>

<div class="modal fade" id="deleteDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este documento? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteDocumentForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Manejar la eliminación de documentos
        document.querySelectorAll('.delete-document').forEach(button => {
            button.addEventListener('click', function() {
                const documentId = this.getAttribute('data-id');
                const form = document.getElementById('deleteDocumentForm');
                form.action = `/documents/${documentId}`;
                
                const modal = new bootstrap.Modal(document.getElementById('deleteDocumentModal'));
                modal.show();
            });
        });
    });
</script>
@endpush

<!-- Incluir el componente de validación de documentos -->
@include('components.validation-modal')

@endsection