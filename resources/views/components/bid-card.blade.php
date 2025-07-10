@props([
    'bid',
    'showRelated' => true
])

@php
    $relatedItem = $bid->bideable;
    $isCarga = $relatedItem instanceof \App\Models\OfertaCarga;
    $tipo = $isCarga ? 'Carga' : 'Ruta';
    $fecha = $bid->fecha_hora->format('d/m/Y H:i');
    $estadoClass = [
        'pendiente' => 'bg-warning',
        'aceptada' => 'bg-success',
        'rechazada' => 'bg-danger',
        'cancelada' => 'bg-secondary',
        'completada' => 'bg-info'
    ][$bid->estado] ?? 'bg-secondary';
@endphp

<div class="card mb-3 border border-light-subtle shadow-sm" style="transition: all 0.2s;">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h5 class="mb-1">Puja #{{ $bid->id }}</h5>
                <p class="text-muted small mb-2">
                    <i class="fas {{ $isCarga ? 'fa-box' : 'fa-route' }} me-1"></i>
                    {{ $tipo }}: {{ $isCarga ? $relatedItem->origen . ' a ' . $relatedItem->destino : $relatedItem->origen . ' - ' . $relatedItem->destino }}
                </p>
            </div>
            <span class="badge {{ $estadoClass }} text-white">
                {{ ucfirst($bid->estado) }}
            </span>
        </div>

        <div class="d-flex align-items-center mb-2">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px;">
                <i class="fas fa-user text-secondary" style="font-size: 0.8rem;"></i>
            </div>
            <div>
                <span class="fw-medium me-2">{{ $bid->user->name }}</span>
                <span class="small text-muted">{{ $fecha }}</span>
            </div>
        </div>

        <div class="border-top border-bottom py-2 my-2 border-light">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded p-2 me-3">
                            <i class="fas fa-dollar-sign text-primary"></i>
                        </div>
                        <div>
                            <p class="small text-muted mb-0">Monto ofertado</p>
                            <p class="mb-0 fw-bold text-primary">${{ number_format($bid->monto, 2) }} USD</p>
                        </div>
                    </div>
                </div>
                @if($bid->comentario)
                <div class="col-md-6">
                    <div class="d-flex align-items-start">
                        <div class="bg-light rounded p-2 me-3 mt-1">
                            <i class="fas fa-comment text-primary"></i>
                        </div>
                        <div>
                            <p class="small text-muted mb-0">Comentario</p>
                            <p class="mb-0 small">{{ Str::limit($bid->comentario, 30) }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($showRelated)
        <div class="mt-3 pt-3 border-top">
            <h6 class="text-muted small mb-2">Detalles de la {{ $tipo }}:</h6>
            @if($isCarga)
                <p class="mb-1 small">
                    <i class="fas fa-boxes text-secondary me-1"></i>
                    Tipo de carga: {{ $relatedItem->cargoType->name ?? 'N/A' }}
                </p>
                <p class="mb-1 small">
                    <i class="fas fa-weight-hanging text-secondary me-1"></i>
                    Peso: {{ number_format($relatedItem->peso, 2) }} kg
                </p>
                <p class="mb-1 small">
                    <i class="far fa-calendar-alt text-secondary me-1"></i>
                    Fecha de carga: {{ $relatedItem->fecha_inicio->format('d/m/Y') }}
                </p>
            @else
                <p class="mb-1 small">
                    <i class="fas fa-truck text-secondary me-1"></i>
                    Tipo de camión: {{ $relatedItem->truckType->name ?? 'N/A' }}
                </p>
                <p class="mb-1 small">
                    <i class="fas fa-weight-hanging text-secondary me-1"></i>
                    Capacidad: {{ number_format($relatedItem->capacidad, 2) }} kg
                </p>
                <p class="mb-1 small">
                    <i class="far fa-calendar-alt text-secondary me-1"></i>
                    Fecha de salida: {{ $relatedItem->fecha_inicio->format('d/m/Y') }}
                </p>
            @endif
        </div>
        @endif

        <div class="d-flex justify-content-between align-items-center pt-2">
            <div class="text-muted small">
                {{ $bid->created_at->locale('es')->diffForHumans() }}
            </div>
            <a href="{{ route('bids.show', $bid->id) }}" class="btn btn-sm btn-primary py-1">
                <i class="fas fa-comments me-1"></i> Ver detalles/chat
            </a>
        </div>
    </div>
</div>
