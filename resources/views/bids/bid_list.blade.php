@extends('layouts.home')

@section('title', 'Listado de Pujas - Mi Aplicación')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div class="d-flex align-items-center mb-2 mb-md-0">
        <h2 class="mb-0 me-4">Listado de Pujas</h2>
    </div>
    <div class="d-flex flex-column flex-md-row gap-3">
        <div class="search-container" style="width: 250px;">
        </div>
        <div class="filter-container">
            <form action="{{ route('bids.index') }}" method="GET" class="d-flex">
                <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                    <option value="aceptado" {{ request('estado') == 'aceptado' ? 'selected' : '' }}>Aceptadas</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazadas</option>
                    <option value="terminado" {{ request('estado') == 'terminado' ? 'selected' : '' }}>Terminadas</option>
                </select>
                @if(request('estado'))
                    <a href="{{ route('bids.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
                @foreach(request()->except('estado', 'page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="mb-3">
                <input type="text" id="searchBids" class="form-control" placeholder="Buscar por ID, Postor o Propietario Oferta..." style="max-width: 400px;">
            </div>
        </div>
    </div>

    {{-- Paginación personalizada --}}
    <x-paginator :paginator="$bids" />

    <div class="card shadow mb-4 mt-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="bidsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Postor</th>
                            <th>Propietario Oferta</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bids as $bid)
                        @php
                            $propietario = $bid->propietario();
                        @endphp
                        <tr class="bid-row" 
                            data-bid-id="{{ $bid->id }}" 
                            data-postor="{{ $bid->user ? $bid->user->name : 'N/A' }}" 
                            data-propietario="{{ $propietario ? $propietario->name : 'N/A' }}">
                            <td>#{{ $bid->id }}</td>
                            <td>
                                @if($bid->user)
                                    <a href="{{ route('users.show', $bid->user) }}" class="user-link" title="">
                                        {{ $bid->user->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if($propietario)
                                    <a href="{{ route('users.show', $propietario) }}" class="user-link" title="">
                                        {{ $propietario->name }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @php
                                    $tipo = $bid->tipo_objeto;
                                @endphp
                                <span class="d-flex align-items-center">
                                    <i class="fas {{ $tipo === 'Ruta' ? 'fa-route' : 'fa-box' }} me-2"></i>
                                    {{ $tipo }}
                                </span>
                            </td>
                            <td>${{ number_format($bid->monto, 2) }}</td>
                            <td>{{ $bid->fecha_hora->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $bid->estado === 'aceptado' ? 'success' : ($bid->estado === 'rechazado' || $bid->estado === 'cancelado' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($bid->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('bids.show', $bid->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center" data-bs-toggle="tooltip" title="Ver detalles y chat">
                                    <i class="fas fa-eye me-1"></i>
                                    <i class="fas fa-comment"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay ofertas registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn i {
        font-size: 0.9em;
    }
    .btn i:first-child {
        margin-right: 3px;
    }
    .badge {
        font-size: 0.85em;
        padding: 0.4em 0.8em;
    }
    .btn-sm {
        margin: 0 2px;
    }
    .user-link {
        color: #000000;
        text-decoration: none;
        position: relative;
        cursor: pointer;
    }
    .user-link:hover::after {
        content: 'Ver detalles del usuario';
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
        margin-bottom: 5px;
    }
    
    /* Estilos para el buscador */
    #searchBids {
        border: 1px solid #dee2e6;
        transition: all 0.2s;
        padding: 0.5rem 0.75rem;
    }
    
    #searchBids:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: 0;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Buscador personalizado
    const searchInput = document.getElementById('searchBids');
    
    if (searchInput) {
        console.log('Buscador de pujas inicializado correctamente');
        
        // Debug: verificar cuántas filas hay
        const rows = document.querySelectorAll('tr.bid-row');
        console.log('Total de filas encontradas:', rows.length);
        
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase().trim();
            console.log('Buscando:', filter);
            
            let visibleCount = 0;
            document.querySelectorAll('tr.bid-row').forEach(function(row) {
                const bidId = (row.getAttribute('data-bid-id') || '').toString();
                const postor = (row.getAttribute('data-postor') || '').toLowerCase();
                const propietario = (row.getAttribute('data-propietario') || '').toLowerCase();
                
                // Debug: mostrar valores
                if (filter !== '') {
                    console.log('Fila - ID:', bidId, 'Postor:', postor, 'Propietario:', propietario);
                }
                
                // Verificar si coincide con ID, postor o propietario
                const matchesSearch = filter === '' || 
                                      bidId.includes(filter) || 
                                      postor.includes(filter) || 
                                      propietario.includes(filter);
                
                // Mostrar/ocultar fila
                if (matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            console.log('Filas visibles:', visibleCount);
        });
    } else {
        console.error('No se encontró el input searchBids');
    }
});
</script>
@endpush
