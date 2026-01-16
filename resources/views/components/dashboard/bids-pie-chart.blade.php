@php
// Mapeo de estados a sus etiquetas y colores
$estados = [
    'aceptado' => [
        'label' => 'Aceptadas',
        'color' => '#10B981', // verde
        'key' => 'aceptadas'
    ],
    'rechazado' => [
        'label' => 'Rechazadas',
        'color' => '#EF4444', // rojo
        'key' => 'rechazadas'
    ],
    'pendiente' => [
        'label' => 'Pendientes',
        'color' => '#F59E0B', // amarillo
        'key' => 'pendientes'
    ],
    'terminado' => [
        'label' => 'Terminadas',
        'color' => '#3B82F6', // azul
        'key' => 'terminadas'
    ]
];

// Preparar datos para el gráfico
$labels = [];
$data = [];
$bgColors = [];

foreach ($estados as $estado) {
    $count = $bidsStats[$estado['key']] ?? 0;
    $labels[] = $estado['label'];
    $data[] = $count;
    $bgColors[] = $estado['color'];
}

$chartId = 'pieChart' . uniqid();
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-white border-0">
        <h6 class="mb-0 fw-bold">Distribución de Estados</h6>
    </div>
    <div class="card-body p-0">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="position-relative" style="height: 250px; width: 100%;">
                    <canvas id="{{ $chartId }}"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="legend-container p-3">
                    @foreach($estados as $estado)
                    <div class="d-flex align-items-center mb-2">
                        <span class="legend-color" style="background-color: {{ $estado['color'] }}; width: 16px; height: 16px; border-radius: 50%; display: inline-block; margin-right: 8px;"></span>
                        <span class="small">{{ $estado['label'] }}: <strong>{{ $bidsStats[$estado['key']] ?? 0 }}</strong></span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
    // Registrar el plugin de datalabels
    Chart.register(ChartDataLabels);
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($data),
                backgroundColor: @json($bgColors),
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                },
                datalabels: {
                    formatter: (value) => value > 0 ? value : '',
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                }
            },
            cutout: '60%',
            borderRadius: 5
        }
    });
});
</script>
@endpush
