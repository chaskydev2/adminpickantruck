@php
// Colores para las barras
$colors = [
    'primary' => [
        'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
        'borderColor' => 'rgba(59, 130, 246, 1)',
        'hoverBackgroundColor' => 'rgba(59, 130, 246, 0.9)'
    ]
];

$chartId = 'barChart' . uniqid();
@endphp

<div class="position-relative" style="height: 350px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-bold">Distribución por Tipo</h6>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary active" data-chart-type="cargas">
                Cargas
            </button>
            <button type="button" class="btn btn-outline-primary" data-chart-type="rutas">
                Rutas
            </button>
        </div>
    </div>
    
    <canvas id="{{ $chartId }}"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
    
    // Datos para los gráficos
    const chartData = {
        cargas: {
            labels: @json($chartData['cargas']->pluck('nombre')),
            data: @json($chartData['cargas']->pluck('total')),
            label: 'Cargas por Tipo',
            backgroundColor: '{{ $colors['primary']['backgroundColor'] }}',
            borderColor: '{{ $colors['primary']['borderColor'] }}',
            hoverBackgroundColor: '{{ $colors['primary']['hoverBackgroundColor'] }}'
        },
        rutas: {
            labels: @json($chartData['rutas']->pluck('nombre')),
            data: @json($chartData['rutas']->pluck('total')),
            label: 'Rutas por Tipo de Camión',
            backgroundColor: '{{ $colors['primary']['backgroundColor'] }}',
            borderColor: '{{ $colors['primary']['borderColor'] }}',
            hoverBackgroundColor: '{{ $colors['primary']['hoverBackgroundColor'] }}'
        }
    };
    
    // Registrar el plugin de datalabels
    Chart.register(ChartDataLabels);
    
    // Configuración del gráfico
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.cargas.labels,
            datasets: [{
                label: chartData.cargas.label,
                data: chartData.cargas.data,
                backgroundColor: chartData.cargas.backgroundColor,
                borderColor: chartData.cargas.borderColor,
                borderWidth: 1,
                hoverBackgroundColor: chartData.cargas.hoverBackgroundColor,
                barThickness: 'flex',
                maxBarThickness: 40,
                minBarLength: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 10
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, stepSize: 1 },
                    grid: { display: true, color: 'rgba(0, 0, 0, 0.05)' }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.raw} ${context.dataset.label.toLowerCase().includes('carga') ? 'cargas' : 'rutas'}`;
                        },
                        title: function(tooltipItems) {
                            return tooltipItems[0].label;
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: (value) => value > 0 ? value : '',
                    color: '#1f2937',
                    font: {
                        weight: 'bold',
                        size: 11
                    }
                }
            },
            interaction: { mode: 'index', intersect: false }
        }
    });
    
    // Manejador para cambiar entre gráficos
    document.querySelectorAll('[data-chart-type]').forEach(button => {
        button.addEventListener('click', function() {
            // Actualizar botones activos
            document.querySelectorAll('[data-chart-type]').forEach(btn => {
                btn.classList.toggle('active', btn === this);
            });
            
            // Obtener el tipo de gráfico seleccionado
            const type = this.dataset.chartType;
            const data = chartData[type];
            
            // Actualizar el gráfico
            chart.data.labels = data.labels;
            chart.data.datasets[0] = {
                label: data.label,
                data: data.data,
                backgroundColor: data.backgroundColor,
                borderColor: data.borderColor,
                borderWidth: 1,
                hoverBackgroundColor: data.hoverBackgroundColor,
                barThickness: 'flex',
                maxBarThickness: 40,
                minBarLength: 2,
            };
            
            chart.update();
        });
    });
});
</script>
@endpush
