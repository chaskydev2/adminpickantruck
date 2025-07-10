@extends('layouts.home')

@section('title', 'Lista de Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <h2 class="mb-0 me-4">Lista de Usuarios</h2>
    </div>
    <div id="search-user-component">
        <search-user></search-user>
    </div>
</div>

<x-paginator :paginator="$users" />

<div class="card shadow-sm mt-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Documentos</th>
                        <th>Fecha de Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="user-row" data-user-id="{{ $user->id }}">
                        <td class="fw-semibold">#{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->detail && $user->detail->role)
                                @php
                                    $roleText = $user->detail->role === 'carrier' ? 'Transportista' : 
                                              ($user->detail->role === 'admin' ? 'Administrador' : ucfirst($user->detail->role));
                                @endphp
                                <span class="badge bg-{{ 
                                    $user->detail->role === 'admin' ? 'danger' : 
                                    (in_array($user->detail->role, ['carrier', 'transportista']) ? 'primary' : 'secondary') 
                                }}">
                                    {{ $roleText }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin rol</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->documents_count > 0 ? 'primary' : 'secondary' }}">
                                {{ $user->documents_count }} {{ Str::plural('documento', $user->documents_count) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->verified)
                                <span class="badge bg-success">Verificado</span>
                            @else
                                <span class="badge bg-warning text-dark">Sin verificar</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-info toggle-documents" 
                                        data-user-id="{{ $user->id }}"
                                        data-bs-toggle="tooltip" 
                                        title="Ver documentos">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                                <a href="{{ route('users.show', $user->id) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="detail-row" id="detail-row-{{ $user->id }}">
                        <td colspan="7" class="p-0">
                            <div class="document-list">
                                <div class="p-3 bg-light">
                                    <h6 class="mb-3">Documentos del Usuario</h6>
                                    @if($user->documents_count > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover bg-white">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo</th>
                                                        <th>Estado</th>
                                                        <th>Fecha de Subida</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($user->documents as $document)
                                                    <tr>
                                                        <td>{{ $document->requiredDocument->name ?? 'Documento' }}</td>
                                                        <td>
                                                            @if($document->status === 'aprobado')
                                                                <span class="badge bg-success">Aprobado</span>
                                                            @elseif($document->status === 'rechazado')
                                                                <span class="badge bg-danger">Rechazado</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                                        <td>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-primary open-validation-modal" 
                                                                    data-document-id="{{ $document->id }}"
                                                                    data-status="{{ $document->status }}"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Ver documento">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @php
                                            $allDocumentsApproved = $user->documents->every(function($doc) {
                                                return $doc->status === 'aprobado';
                                            });
                                        @endphp
                                        <div class="mt-3 text-end">
                                            @if($user->verified)
                                                <button type="button" 
                                                        class="btn btn-warning unverify-user" 
                                                        data-user-id="{{ $user->id }}"
                                                        data-bs-toggle="tooltip" 
                                                        title="Desverificar este usuario">
                                                    <i class="fas fa-user-times me-1"></i> Desverificar Usuario
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="btn btn-{{ $allDocumentsApproved ? 'success' : 'secondary' }} verify-user" 
                                                        data-user-id="{{ $user->id }}"
                                                        {{ !$allDocumentsApproved ? 'disabled' : '' }}
                                                        data-bs-toggle="tooltip" 
                                                        title="{{ $allDocumentsApproved ? 'Verificar este usuario' : 'Todos los documentos deben estar aprobados' }}">
                                                    <i class="fas fa-user-check me-1"></i> Verificar Usuario
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-2"></i> El usuario no ha subido documentos.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No hay usuarios registrados</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('components.validation-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Ocultar todas las filas de detalles al cargar
    document.querySelectorAll('.detail-row').forEach(row => {
        row.style.display = 'none';
    });

    // Mostrar/ocultar documentos del usuario
    document.querySelectorAll('.toggle-documents').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const detailRow = document.getElementById(`detail-row-${userId}`);
            
            // Cerrar otros detalles abiertos
            document.querySelectorAll('.detail-row').forEach(row => {
                if (row.id !== `detail-row-${userId}`) {
                    row.style.display = 'none';
                    const otherButton = document.querySelector(`.toggle-documents[data-user-id="${row.id.split('-')[2]}"]`);
                    if (otherButton) {
                        otherButton.innerHTML = '<i class="fas fa-file-alt"></i>';
                        otherButton.classList.remove('active');
                    }
                }
            });
            
            // Alternar la fila de detalles
            if (detailRow.style.display === 'none') {
                detailRow.style.display = 'table-row';
                this.innerHTML = '<i class="fas fa-times"></i>';
                this.classList.add('active');
            } else {
                detailRow.style.display = 'none';
                this.innerHTML = '<i class="fas fa-file-alt"></i>';
                this.classList.remove('active');
            }
            
            // Actualizar tooltips después de cambiar el contenido
            const tooltip = bootstrap.Tooltip.getInstance(this);
            if (tooltip) {
                tooltip.dispose();
                new bootstrap.Tooltip(this);
            }
        });
    });

    // Función para actualizar el estado del usuario
    function updateUserVerification(userId, verify, button) {
        const action = verify ? 'verificar' : 'desverificar';
        if (!confirm(`¿Estás seguro de ${action} a este usuario?`)) return false;
        
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status"></span> ${verify ? 'Verificando...' : 'Desverificando...'}`;
        
        return fetch(`/usuarios/${userId}/toggle-verification`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                verified: verify
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Recargar la página para actualizar el estado
                window.location.reload();
            } else {
                throw new Error(data.message || `Error al ${action} el usuario`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error al ${action} el usuario: ` + error.message);
            button.disabled = false;
            button.innerHTML = originalHTML;
        });
    }

    // Manejar la verificación de usuario
    document.addEventListener('click', function(event) {
        // Verificar si se hizo clic en el botón de verificar
        const verifyButton = event.target.closest('.verify-user');
        if (verifyButton) {
            event.preventDefault();
            const userId = verifyButton.getAttribute('data-user-id');
            updateUserVerification(userId, true, verifyButton);
            return;
        }
        
        // Verificar si se hizo clic en el botón de desverificar
        const unverifyButton = event.target.closest('.unverify-user');
        if (unverifyButton) {
            event.preventDefault();
            const userId = unverifyButton.getAttribute('data-user-id');
            updateUserVerification(userId, false, unverifyButton);
            return;
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.document-list {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    margin: 0.5rem 0;
}

.document-list table {
    margin-bottom: 0;
}

.document-list .table th {
    background-color: #e9ecef;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.document-list .table td {
    vertical-align: middle;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
}

.toggle-documents.active {
    background-color: #0dcaf0;
    color: white;
}
</style>
@endpush
@endsection