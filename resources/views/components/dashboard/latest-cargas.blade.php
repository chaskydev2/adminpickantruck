<div class="card shadow-sm mt-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Últimas Cargas Publicadas</h5>
        <a href="{{ route('cargas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Origen - Destino</th>
                        <th>Tipo</th>
                        <th>Peso</th>
                        <th>Presupuesto</th>
                        <th>Pujas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestCargas as $carga)
                    <tr>
                        <td>
                            <a href="{{ route('users.show', $carga->user_id) }}" 
                               class="text-primary text-decoration-none"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Ver perfil">
                                {{ Str::limit($carga->user->name, 10) }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-nowrap">{{ $carga->origen }}</span>
                                <small class="text-muted">{{ $carga->destino }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $carga->cargoType->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>{{ number_format($carga->peso, 0) }} kg</td>
                        <td>${{ number_format($carga->presupuesto, 0) }}</td>
                        <td>
                            <span class="badge bg-secondary rounded-pill px-2">
                                {{ $carga->ofertas_recibidas ?? 0 }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-box-open fa-2x mb-2 text-muted"></i>
                            <p class="mb-0">No hay cargas recientes</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
