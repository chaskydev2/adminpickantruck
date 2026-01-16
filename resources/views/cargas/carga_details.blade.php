@extends('layouts.home')

@section('title', 'Detalles Completos de la Carga #' . $carga->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <h2 class="mb-0">Detalles de la Carga #{{ $carga->id }}</h2>
            <span class="badge bg-primary ms-3">
                <i class="fas fa-box me-1"></i>{{ $carga->cargoType->name ?? 'No especificado' }}
            </span>
        </div>
        <div>
            <a href="{{ route('cargas.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda -->
        <div class="col-lg-4">
            <!-- Información del Publicador -->
            <div class="card shadow-sm mb-4" style="position: sticky; top: 20px; height: fit-content; border: 1px solid rgba(0,0,0,0.1);">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-center mb-4">Publicado por</h5>
                    
                    <!-- Avatar centrado -->
                    <div class="text-center mb-4">
                        <div class="d-inline-block position-relative">
                            <img src="{{ $carga->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($carga->user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                 alt="{{ $carga->user->name }}" 
                                 class="rounded-circle border border-3 border-primary" 
                                 width="100" 
                                 height="100">
                        </div>
                    </div>
                    
                    <!-- Información del usuario -->
                    <div class="text-center mb-4">
                        <h5 class="fw-bold mb-2">{{ $carga->user->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-user-clock me-1"></i>Miembro desde {{ $carga->user->created_at->format('M Y') }}
                        </p>
                        
                        <!-- Botones de contacto -->
                        <div class="d-flex gap-2 justify-content-center mb-4">
                            <a href="tel:{{ $carga->user->phone ?? '#' }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone-alt me-1"></i> Llamar
                            </a>
                            <a href="mailto:{{ $carga->user->email }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Mensaje
                            </a>
                        </div>
                    </div>
                    
                    <!-- Botón de ver perfil -->
                    <div class="mt-auto">
                        <a href="{{ route('users.show', $carga->user_id) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-eye me-1"></i> Ver perfil completo
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha - Contenido Principal -->
        <div class="col-lg-8">
            <div class="card shadow-sm" style="border: 1px solid rgba(0,0,0,0.1);">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom border-0" role="tablist" style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#detalles" role="tab">
                                <i class="fas fa-info-circle me-1"></i> Detalles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pujas" role="tab">
                                <i class="fas fa-gavel me-1"></i> Pujas ({{ $carga->bids->count() }})
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Pestaña de Detalles -->
                        <div class="tab-pane active" id="detalles" role="tabpanel">
                            <div class="row">
                                <!-- Información de la Ruta -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-route me-2 text-primary"></i>Ruta
                                    </h5>
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="me-3">
                                            <span class="badge bg-primary rounded-circle p-2">
                                                <i class="fas fa-map-marker-alt text-white"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-muted small">Origen</p>
                                            <p class="mb-0 fw-medium">{{ $carga->origen }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <span class="badge bg-success rounded-circle p-2">
                                                <i class="fas fa-flag-checkered text-white"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="mb-1 text-muted small">Destino</p>
                                            <p class="mb-0 fw-medium">{{ $carga->destino }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detalles de la Carga -->
                                <div class="col-md-6 mb-4">
                                    <h5 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-boxes me-2 text-primary"></i>Detalles
                                    </h5>
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <p class="mb-1 text-muted small">Peso</p>
                                            <p class="mb-0 fw-medium">{{ number_format($carga->peso, 2) }} kg</p>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <p class="mb-1 text-muted small">Presupuesto</p>
                                            <p class="mb-0 fw-bold text-primary">${{ number_format($carga->presupuesto, 2) }} USD</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Fechas Importantes -->
                                <div class="col-12">
                                    <h5 class="mb-3 border-bottom pb-2">
                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>Fechas
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-light rounded p-2 me-3">
                                                    <i class="fas fa-calendar-plus text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 text-muted small">Publicación</p>
                                                    <p class="mb-0 fw-medium">{{ $carga->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-light rounded p-2 me-3">
                                                    <i class="fas fa-truck-loading text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="mb-0 text-muted small">Fecha de Carga</p>
                                                    <p class="mb-0 fw-medium">{{ $carga->fecha_inicio->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($carga->descripcion)
                                <div class="border-top pt-3 mt-3">
                                    <h5 class="mb-3">Descripción Adicional</h5>
                                    <p class="mb-0">{{ $carga->descripcion }}</p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Pestaña de Pujas -->
                        <div class="tab-pane" id="pujas" role="tabpanel">
                            @if($carga->bids->count() > 0)
                                <div class="p-0 m-0">
                                    <div class="row g-3">
                                    @foreach($carga->bids as $bid)
                                        <div class="col-12">
                                            <x-bid-card :bid="$bid" :showRelated="false" />
                                        </div>
                                    @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No hay pujas registradas para esta carga</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-xxl {
        width: 6rem;
        height: 6rem;
    }
    .avatar-title {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
    }
    .nav-tabs-custom .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
    }
    .nav-tabs-custom .nav-link.active {
        color: #4e73df;
        border-bottom: 2px solid #4e73df;
        background: transparent;
    }
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    .card-title {
        color: #4e73df;
        font-weight: 600;
    }
    .badge {
        font-weight: 500;
        padding: 0.5em 0.8em;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush