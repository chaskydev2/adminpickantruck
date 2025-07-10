@props([
    'ruta'
])

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            {{-- "hace X tiempo" a la izquierda --}}
            <span class="text-muted small">
                hace {{ $ruta->created_at->locale('es')->diffForHumans(null, true) }}
            </span>

            {{-- Contador de ofertas a la derecha, en el encabezado --}}
            <div class="d-flex align-items-center">
                <p class="mb-0 me-2 text-dark fw-bold small">Ofertas recibidas:</p>
                <span class="badge bg-secondary rounded-pill px-2 py-1">
                    {{ $ruta->ofertas_recibidas }}
                </span>
            </div>
        </div>

        {{-- Tipo de vehículo debajo del encabezado --}}
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold me-2">Vehículo:</p>
                <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                    {{ $ruta->truckType->name ?? 'No especificado' }}
                </span>
            </div>
        </div>

        <div class="mb-3">
            {{-- Origen y Destino - más grandes y juntos --}}
            <div class="d-flex align-items-center">
                <p class="mb-0 fw-bold me-2 h5"><i class="fas fa-map-marker-alt text-secondary me-1"></i> {{ $ruta->origen }}</p>
                <i class="fas fa-long-arrow-alt-right text-secondary mx-2 fa-lg"></i>
                <p class="mb-0 fw-bold h5"><i class="fas fa-map-marker-alt text-secondary me-1"></i> {{ $ruta->destino }}</p>
            </div>
        </div>

        <hr class="my-3"> {{-- Separador --}}

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            {{-- Fecha de salida --}}
            <div class="d-flex align-items-center me-3 my-1">
                <div class="bg-light rounded p-2 me-2">
                    <i class="far fa-calendar-alt text-secondary"></i>
                </div>
                <div>
                    <p class="small text-muted mb-0">Fecha de salida</p>
                    <p class="mb-0 fw-medium">{{ $ruta->fecha_inicio->format('d/m/Y') }}</p>
                </div>
            </div>
            
            {{-- Capacidad --}}
            <div class="d-flex align-items-center me-3 my-1">
                <div class="bg-light rounded p-2 me-2">
                    <i class="fas fa-weight-hanging text-secondary"></i>
                </div>
                <div>
                    <p class="small text-muted mb-0">Capacidad</p>
                    <p class="mb-0 fw-medium">{{ number_format($ruta->capacidad, 2, ',', '.') }} kg</p>
                </div>
            </div>
            
            {{-- Precio referencial --}}
            <div class="d-flex align-items-center my-1">
                <div class="bg-light rounded p-2 me-2">
                    <i class="fas fa-dollar-sign text-secondary"></i>
                </div>
                <div>
                    <p class="small text-muted mb-0">Precio ref.</p>
                    <p class="mb-0 fw-medium">{{ $ruta->precio_formateado }}</p>
                </div>
            </div>
        </div>

        {{-- Botón de Ver Detalles --}}
        <div class="d-grid">
            <a href="{{ route('rutas.show', $ruta) }}" class="btn btn-outline-primary w-100 py-2">
                <i class="far fa-eye me-2"></i> Ver detalles
            </a>
        </div>
    </div>
</div>
