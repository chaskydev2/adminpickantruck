@extends('layouts.home')

@section('title', 'Lista de Cargas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <h2 class="mb-0 me-4">Lista de Publicaciones de Cargas</h2>
    </div>
    <div class="search-container" style="width: 400px;">
    </div>
</div>

@push('scripts')
    @vite(['resources/js/app.js'])
@endpush

<div class="mb-3 d-flex gap-3">
    <input type="text" id="searchCargas" class="form-control" placeholder="Buscar por ID, Usuario, Tipo de Carga, Origen o Destino..." style="max-width: 500px;">
    
    <select id="filterEstado" class="form-select" style="max-width: 200px;">
        <option value="todos">Todos</option>
        <option value="activo">Activo</option>
        <option value="con_ofertas">Con Ofertas</option>
        <option value="asignado">Asignado</option>
        <option value="eliminado">Eliminado</option>
    </select>
</div>

{{-- Paginación personalizada --}}
<x-paginator :paginator="$cargas" />

<div class="card shadow-sm mt-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Tipo de Carga</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Peso (kg)</th>
                        <th>Fecha de Carga</th>
                        <th>Presupuesto</th>
                        <th>Estado</th>
                        <th>Pujas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cargas as $carga)
                        @php
                            $isDeleted = $carga->isDeleted();
                            $hasAcceptedBid = !$isDeleted && $carga->bids()->where('estado', 'aceptado')->exists();
                            $hasPendingBid = !$isDeleted && $carga->bids()->where('estado', 'pendiente')->exists();
                            
                            if ($isDeleted) {
                                $estadoClass = 'eliminado';
                            } elseif ($hasAcceptedBid) {
                                $estadoClass = 'asignado';
                            } elseif ($hasPendingBid) {
                                $estadoClass = 'con_ofertas';
                            } else {
                                $estadoClass = 'activo';
                            }
                        @endphp
                        <tr class="carga-row"
                            data-carga-id="{{ $carga->id }}"
                            data-usuario="{{ $carga->user->name }}"
                            data-tipo-carga="{{ $carga->cargoType->name ?? 'N/A' }}"
                            data-origen="{{ $carga->origen }}"
                            data-destino="{{ $carga->destino }}"
                            data-estado="{{ $estadoClass }}">
                            <td>#{{ $carga->id }}</td>
                            <td>
                                <a href="{{ route('users.show', $carga->user_id) }}" 
                                   class="text-dark text-decoration-none"
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalles del usuario">
                                    {{ $carga->user->name }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $carga->cargoType->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ $carga->origen }}</td>
                            <td>{{ $carga->destino }}</td>
                            <td>{{ number_format($carga->peso, 2) }}</td>
                            <td>{{ $carga->fecha_inicio->format('d/m/Y') }}</td>
                            <td>${{ number_format($carga->presupuesto, 2) }} USD</td>
                            <td>
                                @if($isDeleted)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-trash-alt"></i> Eliminado
                                    </span>
                                @elseif($hasAcceptedBid)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Asignado
                                    </span>
                                @elseif($hasPendingBid)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> Con Ofertas
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="fas fa-hourglass-half"></i> Activo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary rounded-pill px-2">
                                    {{ $carga->ofertas_recibidas ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('cargas.show', $carga) }}" 
                                   class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" 
                                   data-bs-toggle="tooltip" 
                                   title="Ver detalles"
                                   style="width: 32px; height: 32px;">
                                    <i class="far fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                                <p class="mb-0">No hay cargas registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración de tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Variables para los filtros
    const searchInput = document.getElementById('searchCargas');
    const filterEstado = document.getElementById('filterEstado');
    
    // Función para aplicar todos los filtros
    function aplicarFiltros() {
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const estadoValue = filterEstado ? filterEstado.value : 'todos';
        
        document.querySelectorAll('tr.carga-row').forEach(function(row) {
            const cargaId = (row.getAttribute('data-carga-id') || '').toString();
            const usuario = (row.getAttribute('data-usuario') || '').toLowerCase();
            const tipoCarga = (row.getAttribute('data-tipo-carga') || '').toLowerCase();
            const origen = (row.getAttribute('data-origen') || '').toLowerCase();
            const destino = (row.getAttribute('data-destino') || '').toLowerCase();
            const estado = row.getAttribute('data-estado') || '';
            
            // Verificar si coincide con búsqueda
            const matchesSearch = searchValue === '' || 
                                  cargaId.includes(searchValue) || 
                                  usuario.includes(searchValue) || 
                                  tipoCarga.includes(searchValue) || 
                                  origen.includes(searchValue) || 
                                  destino.includes(searchValue);
            
            // Verificar si coincide con estado
            const matchesEstado = estadoValue === 'todos' || estado === estadoValue;
            
            // Mostrar/ocultar fila
            if (matchesSearch && matchesEstado) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Buscador de cargas
    if (searchInput) {
        searchInput.addEventListener('input', aplicarFiltros);
    }
    
    // Filtro de estado
    if (filterEstado) {
        filterEstado.addEventListener('change', aplicarFiltros);
    }
});
</script>
@endpush

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8em;
        font-weight: 500;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    /* Estilos para el buscador */
    #searchCargas {
        border: 1px solid #dee2e6;
        transition: all 0.2s;
        padding: 0.5rem 0.75rem;
    }
    #searchCargas:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 0;
    }
</style>
@endpush
