<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Panel de Control') }} <span class="badge bg-primary ms-2">{{ $stats['periodo']['texto'] }}</span>
            </h2>
            <div class="d-flex">
                <form method="get" action="{{ route('dashboard') }}">
                    <div class="btn-group" role="group" aria-label="Filtro de periodo">
                        <a href="{{ route('dashboard', ['periodo' => 'all']) }}" 
                           class="btn btn-sm {{ $stats['periodo']['valor'] === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Todo
                        </a>
                        <a href="{{ route('dashboard', ['periodo' => 'today']) }}" 
                           class="btn btn-sm {{ $stats['periodo']['valor'] === 'today' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Hoy
                        </a>
                        <a href="{{ route('dashboard', ['periodo' => 'week']) }}" 
                           class="btn btn-sm {{ $stats['periodo']['valor'] === 'week' ? 'btn-primary' : 'btn-outline-primary' }}">
                            7 días
                        </a>
                        <a href="{{ route('dashboard', ['periodo' => 'month']) }}" 
                           class="btn btn-sm {{ $stats['periodo']['valor'] === 'month' ? 'btn-primary' : 'btn-outline-primary' }}">
                            30 días
                        </a>
                        <a href="{{ route('dashboard', ['periodo' => 'year']) }}" 
                           class="btn btn-sm {{ $stats['periodo']['valor'] === 'year' ? 'btn-primary' : 'btn-outline-primary' }}">
                            1 año
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container px-4">
            <!-- Estadísticas Principales -->
            <div class="row mb-5">
                <!-- Usuarios -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-primary mb-0">
                                    <i class="fas fa-users me-2"></i>Usuarios
                                </h5>
                                <span class="badge bg-primary">Total: {{ $stats['usuarios']['total'] }}</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="p-3 bg-success bg-opacity-10 rounded">
                                        <div class="text-success">Verificados</div>
                                        <h3 class="mb-0">{{ $stats['usuarios']['verificados'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                                        <div class="text-warning">Pendientes</div>
                                        <h3 class="mb-0">{{ $stats['usuarios']['pendientes'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-info bg-opacity-10 rounded">
                                        <div class="text-info">Nuevos</div>
                                        <h3 class="mb-0">{{ $stats['usuarios']['nuevos'] }}</h3>
                                        @if($stats['periodo']['valor'] !== 'all')
                                            <small class="text-muted d-block mt-1">en este periodo</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actividad del Sistema (Resumen) -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title text-primary mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Resumen de Actividades
                                </h5>
                                <span class="badge bg-primary">Total: {{ $stats['actividad']['cargas'] + $stats['actividad']['rutas'] + $stats['actividad']['pujas']['total'] }}</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded text-center">
                                        <div class="text-primary">Cargas</div>
                                        <h3 class="mb-0">{{ $stats['actividad']['cargas'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-success bg-opacity-10 rounded text-center">
                                        <div class="text-success">Rutas</div>
                                        <h3 class="mb-0">{{ $stats['actividad']['rutas'] }}</h3>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-info bg-opacity-10 rounded text-center">
                                        <div class="text-info">Pujas</div>
                                        <h3 class="mb-0">{{ $stats['actividad']['pujas']['total'] }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad del Sistema Detallada -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-pie me-2"></i>Estadísticas de Pujas
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Fila principal con contadores principales -->
                            <div class="row g-4">
                                <!-- Total Pujas -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="d-flex p-3 rounded bg-info bg-opacity-10 h-100 align-items-center">
                                        <div class="icon-box rounded-circle bg-info text-white p-3 me-3">
                                            <i class="fas fa-gavel"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $stats['actividad']['pujas']['total'] }}</h3>
                                            <div class="text-muted">Total Pujas</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pujas Aceptadas -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="d-flex p-3 rounded bg-success bg-opacity-10 h-100 align-items-center">
                                        <div class="icon-box rounded-circle bg-success text-white p-3 me-3">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $stats['actividad']['pujas']['aceptadas'] }}</h3>
                                            <div class="text-muted">Pujas Aceptadas</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pujas Pendientes -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="d-flex p-3 rounded bg-warning bg-opacity-10 h-100 align-items-center">
                                        <div class="icon-box rounded-circle bg-warning text-white p-3 me-3">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $stats['actividad']['pujas']['pendientes'] }}</h3>
                                            <div class="text-muted">Pujas Pendientes</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pujas Terminadas -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="d-flex p-3 rounded bg-dark bg-opacity-10 h-100 align-items-center">
                                        <div class="icon-box rounded-circle bg-dark text-white p-3 me-3">
                                            <i class="fas fa-flag-checkered"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $stats['actividad']['pujas']['terminadas'] }}</h3>
                                            <div class="text-muted">Pujas Terminadas</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progreso de estados de pujas y gráficas -->
                            <div class="mt-4">
                                <h6 class="mb-3">Tasas de Conversión</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span>Tasa de Aceptación</span>
                                            <span class="fw-bold">{{ $stats['actividad']['tasas']['aceptacion'] }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                style="width: {{ $stats['actividad']['tasas']['aceptacion'] }}%" 
                                                aria-valuenow="{{ $stats['actividad']['tasas']['aceptacion'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100"></div>
                                        </div>
                                        <div class="small text-muted text-center mt-1">
                                            Porcentaje de pujas que han sido aceptadas
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-2">
                                        <!-- Gráfica circular de distribución de pujas -->
                                        <div>
                                            <canvas id="pujasChart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gráfica de ofertas -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="mb-3">Distribución de Ofertas</h6>
                                        <div style="height: 200px;">
                                            <canvas id="ofertasChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas Actividades -->
            <div class="row mb-5">
                <!-- Últimos Usuarios -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user-clock me-2"></i>Últimos Usuarios Registrados
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['ultimos_usuarios'] as $usuario)
                                        <tr>
                                            <td>{{ $usuario->name }}</td>
                                            <td>{{ $usuario->email }}</td>
                                            <td>
                                                <span class="badge {{ $usuario->email_verified_at ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $usuario->email_verified_at ? 'Verificado' : 'Pendiente' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimas Pujas -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-gavel me-2"></i>Últimas Pujas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($stats['ultimas_actividades']['pujas'] as $puja)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $puja->usuario }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($puja->created_at)->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-info">Bs. {{ number_format($puja->monto, 2) }}</span>
                                                <br>
                                                <small class="badge bg-{{ $puja->estado === 'aceptado' ? 'success' : ($puja->estado === 'rechazado' ? 'danger' : ($puja->estado === 'terminado' ? 'dark' : 'warning')) }}">
                                                    {{ ucfirst($puja->estado) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas Ofertas -->
            <div class="row mb-5">
                <!-- Últimas Ofertas de Carga -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-box me-2"></i>Últimas Ofertas de Carga
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($stats['ultimas_actividades']['cargas'] as $carga)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $carga->origen }} → {{ $carga->destino }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $carga->usuario }}
                                                    <span class="mx-1">•</span>
                                                    <i class="fas fa-box me-1"></i>{{ $carga->tipo_carga }}
                                                </small>
                                            </div>
                                            <span class="badge bg-success">Bs. {{ number_format($carga->presupuesto, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimas Ofertas de Ruta -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-route me-2"></i>Últimas Ofertas de Ruta
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($stats['ultimas_actividades']['rutas'] as $ruta)
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $ruta->origen }} → {{ $ruta->destino }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>{{ $ruta->usuario }}
                                                    <span class="mx-1">•</span>
                                                    <i class="fas fa-truck me-1"></i>{{ $ruta->tipo_camion }}
                                                </small>
                                            </div>
                                            <span class="badge bg-primary">Bs. {{ number_format($ruta->precio_referencial, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            transition: transform 0.2s;
            border-radius: 0.75rem;
            overflow: hidden;
            background-color: #ffffff !important;
            padding: 0.25rem;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-header {
            border-bottom: none;
            padding: 1.25rem 1.5rem;
            background-color: #ffffff !important;
        }
        .rounded {
            border-radius: 0.5rem !important;
        }
        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
        .icon-box {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
            padding-left: 0;
            padding-right: 0;
            background-color: transparent !important;
            color: #333333 !important;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .progress {
            border-radius: 1rem;
            background-color: #f0f0f0;
        }
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }
        /* Asegurar texto oscuro en las tarjetas */
        .card-title, .card-text, .list-group-item, .text-muted {
            color: #333333 !important;
        }
        /* Corregimos el color de los íconos dentro de cajas */
        .icon-box.bg-primary, .icon-box.bg-success, .icon-box.bg-warning, .icon-box.bg-info, .icon-box.bg-dark {
            color: white !important;
        }
        
        /* Estilos adicionales para mejorar el espaciado */
        .row > [class*="col-"] {
            margin-bottom: 1.5rem;
        }
        
        /* Mejorar espaciado en elementos de información */
        .p-3 {
            padding: 1.25rem !important;
        }
        
        /* Añadir espaciado a las tablas */
        .table {
            margin-bottom: 0;
        }
        
        .table th, .table td {
            padding: 0.85rem 1rem;
        }
        
        /* Mejorar espaciado en listas */
        .list-group-item {
            padding-top: 0.85rem;
            padding-bottom: 0.85rem;
        }
        
        /* Añadir espacio a las gráficas */
        canvas {
            padding: 0.5rem;
        }
        
        /* Asegurar Montserrat en elementos del dashboard */
        .card-title {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 600;
        }
        
        h3 {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 600;
        }
        
        .list-group-item h6 {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 500;
        }
        
        .text-muted {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 300;
        }
        
        .badge {
            font-family: 'Montserrat', sans-serif !important;
            font-weight: 500;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar gráfica de pujas
            const pujasCtx = document.getElementById('pujasChart').getContext('2d');
            const pujasData = {
                labels: ['Aceptadas', 'Pendientes', 'Rechazadas', 'Terminadas'],
                datasets: [{
                    data: [
                        {{ $stats['actividad']['pujas']['aceptadas'] }}, 
                        {{ $stats['actividad']['pujas']['pendientes'] }}, 
                        {{ $stats['actividad']['pujas']['rechazadas'] }},
                        {{ $stats['actividad']['pujas']['terminadas'] }}
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)', // Success - verde
                        'rgba(255, 193, 7, 0.7)', // Warning - amarillo
                        'rgba(220, 53, 69, 0.7)', // Danger - rojo
                        'rgba(52, 58, 64, 0.7)'  // Dark - negro
                    ],
                    borderColor: [
                        'rgb(40, 167, 69)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)',
                        'rgb(52, 58, 64)'
                    ],
                    borderWidth: 1
                }]
            };
            
            new Chart(pujasCtx, {
                type: 'doughnut',
                data: pujasData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribución de Pujas',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            });

            // Configurar gráfica de ofertas
            const ofertasCtx = document.getElementById('ofertasChart').getContext('2d');
            const ofertasData = {
                labels: ['Cargas', 'Rutas'],
                datasets: [{
                    label: 'Total de ofertas',
                    data: [
                        {{ $stats['actividad']['cargas'] }},
                        {{ $stats['actividad']['rutas'] }}
                    ],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)', // Primary - azul
                        'rgba(40, 167, 69, 0.7)'  // Success - verde
                    ],
                    borderColor: [
                        'rgb(13, 110, 253)',
                        'rgb(40, 167, 69)'
                    ],
                    borderWidth: 1
                }]
            };
            
            new Chart(ofertasCtx, {
                type: 'bar',
                data: ofertasData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
