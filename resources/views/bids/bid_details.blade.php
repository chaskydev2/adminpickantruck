@extends('layouts.home')

@section('title', 'Detalles de la Oferta #' . $bid->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <h2 class="mb-0">Detalles de la Oferta #{{ $bid->id }}</h2>
            <span class="badge bg-{{ $bid->estado === 'aceptado' ? 'success' : ($bid->estado === 'rechazado' || $bid->estado === 'cancelado' ? 'danger' : 'warning') }} ms-3">
                {{ ucfirst($bid->estado) }}
            </span>
        </div>
        <div>
            <a href="{{ route('bids.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda - Información de los Usuarios -->
        <div class="col-lg-4">
            <!-- Información del Postor -->
            <div class="card shadow-sm mb-4" style="border: 1px solid rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Información del Postor</h5>
                    
                    <div class="text-center mb-3">
                        <div class="d-inline-block position-relative">
                            <img src="{{ $bid->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($bid->user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                 alt="{{ $bid->user->name }}" 
                                 class="rounded-circle border border-3 border-primary" 
                                 width="90" 
                                 height="90">
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <h5 class="fw-bold mb-1">{{ $bid->user->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-user-clock me-1"></i>Miembro desde {{ $bid->user->created_at ? $bid->user->created_at->format('M Y') : 'N/A' }}
                        </p>
                        
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            @if($bid->user->phone)
                            <a href="tel:{{ $bid->user->phone }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone-alt me-1"></i> Llamar
                            </a>
                            @endif
                            <a href="mailto:{{ $bid->user->email }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Email
                            </a>
                            <a href="{{ route('users.show', $bid->user) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-user me-1"></i> Ver Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Propietario de la Oferta -->
            <div class="card shadow-sm" style="border: 1px solid rgba(0,0,0,0.1);">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Propietario de la Oferta</h5>
                    
                    <div class="text-center mb-3">
                        <div class="d-inline-block position-relative">
                            @php
                                $owner = $bid->bideable->user;
                            @endphp
                            <img src="{{ $owner->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($owner->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                 alt="{{ $owner->name }}" 
                                 class="rounded-circle border border-3 border-primary" 
                                 width="90" 
                                 height="90">
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <h5 class="fw-bold mb-1">{{ $owner->name }}</h5>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-user-clock me-1"></i>Miembro desde {{ $owner->created_at->format('M Y') }}
                        </p>
                        
                        <div class="d-flex gap-2 justify-content-center">
                            @if($owner->phone)
                            <a href="tel:{{ $owner->phone }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone-alt me-1"></i> Llamar
                            </a>
                            @endif
                            <a href="mailto:{{ $owner->email }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i> Email
                            </a>
                            <a href="{{ route('users.show', $owner) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-user me-1"></i> Ver Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha - Detalles de la Oferta y Chat -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4" style="border: 1px solid rgba(0,0,0,0.1);">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-custom border-0" role="tablist" style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#detalles" role="tab">
                                <i class="fas fa-info-circle me-1"></i> Detalles de la Oferta
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#chat" role="tab">
                                <i class="fas fa-comments me-1"></i> Chat
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-3">
                        <!-- Pestaña de Detalles -->
                        <div class="tab-pane fade show active" id="detalles" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-4">
                                                <i class="fas fa-info-circle me-2 text-primary"></i>Detalles de la Oferta
                                            </h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <dl class="row">
                                                        <dt class="col-sm-6">ID de la Oferta:</dt>
                                                        <dd class="col-sm-6">#{{ $bid->id }}</dd>
                                                        
                                                        <dt class="col-sm-6">Tipo:</dt>
                                                        <dd class="col-sm-6">
                                                            <span class="badge bg-{{ $bid->bideable_type === 'App\\Models\\Ruta' ? 'info' : 'warning' }}">
                                                                {{ $bid->bideable_type === 'App\\Models\\Ruta' ? 'RUTA' : 'CARGA' }}
                                                            </span>
                                                        </dd>
                                                        
                                                        <dt class="col-sm-6">Monto ofertado:</dt>
                                                        <dd class="col-sm-6">${{ number_format($bid->monto, 2) }}</dd>
                                                        
                                                        <dt class="col-sm-6">Fecha de oferta:</dt>
                                                        <dd class="col-sm-6">{{ $bid->fecha_hora ? $bid->fecha_hora->format('d/m/Y H:i') : 'No especificada' }}</dd>
                                                        
                                                        <dt class="col-sm-6">Estado:</dt>
                                                        <dd class="col-sm-6">
                                                            @php
                                                                $estados = [
                                                                    'pendiente' => ['badge' => 'bg-warning', 'text' => 'Pendiente'],
                                                                    'aceptado' => ['badge' => 'bg-success', 'text' => 'Aceptado'],
                                                                    'rechazado' => ['badge' => 'bg-danger', 'text' => 'Rechazado'],
                                                                    'cancelado' => ['badge' => 'bg-secondary', 'text' => 'Cancelado'],
                                                                ];
                                                                $estado = $estados[$bid->estado] ?? ['badge' => 'bg-secondary', 'text' => ucfirst($bid->estado)];
                                                            @endphp
                                                            <span class="badge {{ $estado['badge'] }}">
                                                                {{ $estado['text'] }}
                                                            </span>
                                                        </dd>
                                                    </dl>
                                                </div>
                                                
                                                @if($bid->comentarios)
                                                <div class="col-md-6">
                                                    <h6 class="mb-2">
                                                        <i class="fas fa-comment me-2 text-primary"></i>Comentarios
                                                    </h6>
                                                    <div class="bg-light p-3 rounded">
                                                        {{ $bid->comentarios }}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i class="fas {{ $bid->bideable_type === 'App\\Models\\Ruta' ? 'fa-route' : 'fa-truck-loading' }} me-2 text-primary"></i>
                                        {{ $bid->bideable_type === 'App\\Models\\Ruta' ? 'Detalles de la Ruta' : 'Detalles de la Carga' }}
                                    </h5>
                                    
                                    @if($bid->bideable_type === 'App\\Models\\Ruta')
                                        <x-ruta-card :ruta="$bid->bideable" />
                                    @else
                                        <x-carga-card :carga="$bid->bideable" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pestaña de Chat -->
                        <div class="tab-pane fade" id="chat" role="tabpanel">
                            <div class="chat-container" style="height: 500px; overflow-y: auto;" id="chat-messages">
                                @forelse($chat->messages as $message)
                                    <div class="mb-3 d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : '' }}">
                                        <div class="message {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }} p-3 rounded-3" 
                                             style="max-width: 70%;">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="fw-bold {{ $message->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                                    {{ $message->user->name }}
                                                </small>
                                                <small class="ms-2 {{ $message->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="message-content">
                                                {{ $message->content }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-comments fa-3x mb-3"></i>
                                        <p>No hay mensajes en este chat</p>
                                    </div>
                                @endforelse
                            </div>
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
    .nav-tabs-custom .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1.25rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .nav-tabs-custom .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom: 3px solid #0d6efd;
    }
    
    .nav-tabs-custom .nav-link:hover:not(.active) {
        border-bottom-color: #dee2e6;
    }
    
    .message {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .chat-container {
        scroll-behavior: smooth;
    }
    
    .chat-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .chat-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .chat-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .chat-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>
@endpush

@push('scripts')
<script>
    // Desplazamiento automático al final del chat
    function scrollToBottom() {
        const chatContainer = document.getElementById('chat-messages');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    
    // Enviar mensaje con AJAX
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Limpiar el campo de entrada
                form.querySelector('input[name="content"]').value = '';
                
                // Opcional: actualizar la interfaz con el nuevo mensaje
                // Esto podría manejarse mejor con WebSockets para una experiencia en tiempo real
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    
    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        // Desplazarse al final del chat al cargar la página
        scrollToBottom();
        
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
