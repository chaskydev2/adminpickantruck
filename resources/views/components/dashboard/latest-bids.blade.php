<div class="card shadow-sm mt-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Últimas Pujas</h5>
        <a href="{{ route('bids.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Postor</th>
                        <th>Monto</th>
                        <th>Dueño Oferta</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestBids as $bid)
                    <tr>
                        <td>
                            @if($bid->user)
                                <a href="{{ route('users.show', $bid->user) }}" 
                                   class="text-primary text-decoration-none"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Ver perfil">
                                    {{ $bid->user->name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>${{ number_format($bid->monto, 2) }}</td>
                        <td>
                            @php
                                $propietario = $bid->propietario();
                            @endphp
                            @if($propietario)
                                <a href="{{ route('users.show', $propietario) }}" 
                                   class="text-primary text-decoration-none"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="Ver perfil">
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
                        <td>
                            <span class="badge bg-{{ $bid->estado === 'aceptado' ? 'success' : ($bid->estado === 'rechazado' || $bid->estado === 'cancelado' ? 'danger' : 'warning') }}">
                                {{ ucfirst($bid->estado) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No hay pujas recientes</td>
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
