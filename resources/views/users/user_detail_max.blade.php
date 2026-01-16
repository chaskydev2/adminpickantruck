@extends('layouts.home')

@section('title', 'Detalles Completos del Usuario')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <h2 class="mb-0 me-4">Detalles Completos del Usuario</h2>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
            <button class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Editar Usuario
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4 border-dark">
                <div class="card-body text-center">
                    <div class="avatar-xxl mx-auto mb-3 position-relative">
                        <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                            <i class="fas fa-user" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $user->name ?? 'Nombre del Usuario' }}</h4>
                    <p class="text-muted mb-3">{{ $user->email ?? 'correo@ejemplo.com' }}</p>
                    
                    <div class="d-flex gap-2 justify-content-center mb-3">
                        @if(isset($user->verified) && $user->verified)
                            <span class="badge bg-success">Verificado</span>
                        @else
                            <span class="badge bg-warning text-dark">No Verificado</span>
                        @endif
                        @if(isset($user->role))
                            @if($user->role === 'forwarder')
                                <span class="badge bg-success">Forwarder</span>
                            @elseif($user->role === 'carrier')
                                <span class="badge bg-primary">Transportista</span>
                            @elseif($user->role === 'admin')
                                <span class="badge bg-danger">Administrador</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Sin rol asignado</span>
                        @endif
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-envelope me-1"></i> Mensaje
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-phone me-1"></i> Llamar
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-dark">
                <div class="card-body">
                    <h5 class="card-title mb-3">Información de Contacto</h5>
                    <div class="mb-3">
                        <p class="mb-1 text-muted">Teléfono</p>
                        <p class="mb-0">
                            @if(isset($user->empresa) && $user->empresa->telefono)
                                {{ $user->empresa->telefono }}
                            @else
                                No especificado
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1 text-muted">Dirección</p>
                        <p class="mb-0">
                            @if(isset($user->empresa) && $user->empresa->direccion)
                                {{ $user->empresa->direccion }}
                            @else
                                No especificada
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="mb-1 text-muted">Sitio Web</p>
                        <p class="mb-0">
                            @if(isset($user->empresa) && $user->empresa->sitio_web)
                                <a href="{{ $user->empresa->sitio_web }}" target="_blank">{{ $user->empresa->sitio_web }}</a>
                            @else
                                No especificado
                            @endif
                        </p>
                    </div>

                    <div class="mt-3">
                        <p class="mb-1 text-muted">Último Acceso</p>
                        <p class="mb-0">
                            @if(isset($user->last_login_at) && $user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                Nunca
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card mb-4 border-dark">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#overview" role="tab">
                                <i class="fas fa-user-circle me-1"></i> Resumen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#carga" role="tab">
                                <i class="fas fa-truck-loading me-1"></i> Publicaciones de Carga
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#ruta" role="tab">
                                <i class="fas fa-route me-1"></i> Publicaciones de Ruta
                            </a>
                        </li>

                    </ul>

                    <div class="tab-content p-3">
                        <div class="tab-pane active" id="overview" role="tabpanel">
                            <h5 class="mb-3">Información del Perfil</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th class="ps-0" style="width: 30%;">ID de Usuario:</th>
                                            <td class="text-muted">#{{ $user->id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Nombre Completo:</th>
                                            <td class="text-muted">{{ $user->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Correo Electrónico:</th>
                                            <td class="text-muted">{{ $user->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Rol:</th>
                                            <td>
                                                @if(isset($user->role))
                                                    @if($user->role === 'forwarder')
                                                        <span class="badge bg-success">Forwarder</span>
                                                    @elseif($user->role === 'carrier')
                                                        <span class="badge bg-primary">Transportista</span>
                                                    @elseif($user->role === 'admin')
                                                        <span class="badge bg-danger">Administrador</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Sin rol asignado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Estado de la Cuenta:</th>
                                            <td>
                                                @if($user->verified)
                                                    <span class="badge bg-success">Verificado</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Sin verificar</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="ps-0">Miembro desde:</th>
                                            <td class="text-muted">
                                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                                                <small class="text-muted">({{ $user->created_at ? $user->created_at->diffForHumans() : '' }})</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="carga" role="tabpanel">
                            @if($user->ofertasCarga->count() > 0)
                                <div class="row g-3">
                                    @foreach($user->ofertasCarga as $carga)
                                        <div class="col-12">
                                            <x-carga-card :carga="$carga" />
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-truck-loading text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">No hay publicaciones de carga</h5>
                                    <p class="text-muted small">Este usuario aún no ha publicado ninguna carga.</p>
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane" id="ruta" role="tabpanel">
                            @if($user->ofertasRuta->count() > 0)
                                <div class="row g-3">
                                    @foreach($user->ofertasRuta as $ruta)
                                        <div class="col-12">
                                            <x-ruta-card :ruta="$ruta" />
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-route text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">No hay publicaciones de ruta</h5>
                                    <p class="text-muted small">Este usuario aún no ha publicado ninguna ruta.</p>
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
    /* Estilos para tarjetas con sombra suave y bordes sutiles */
    .card {
        border: 1px solid #e9ecef !important;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.2s ease-in-out;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }
    
    /* Estilo para las pestañas */
    .nav-tabs {
        border-bottom: 1px solid #e9ecef;
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.75rem 1.25rem;
        margin-right: 0.5rem;
        border-radius: 0.375rem 0.375rem 0 0;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        background-color: #f8f9fa;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        background-color: #fff;
        border-bottom: 2px solid #0d6efd;
        font-weight: 500;
    }
.avatar-xxl {
    height: 7rem;
    width: 7rem;
    line-height: 7rem;
    text-align: center;
    margin: 0 auto;
}
.nav-tabs-custom .nav-link {
    color: #6c757d;
    border: none;
    padding: 0.75rem 1rem;
    font-weight: 500;
}
.nav-tabs-custom .nav-link.active {
    color: #0d6efd;
    background-color: transparent;
    border-bottom: 2px solid #0d6efd;
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
