<div class="card shadow-sm mt-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Últimos Usuarios Registrados</h5>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestUsers as $user)
                    <tr>
                        <td>
                            <a href="{{ route('users.show', $user->id) }}" 
                               class="text-primary text-decoration-none"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Ver perfil">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->detail && $user->detail->role)
                                @php
                                    $roleText = $user->detail->role === 'carrier' ? 'Transportista' : 
                                              ($user->detail->role === 'admin' ? 'Admin' : ucfirst($user->detail->role));
                                @endphp
                                <span class="badge bg-{{ 
                                    $user->detail->role === 'admin' ? 'danger' : 
                                    (in_array($user->detail->role, ['carrier', 'transportista']) ? 'primary' : 'secondary') 
                                }}">
                                    {{ $roleText }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin rol</span>
                            @endif
                        </td>
                        <td>
                            @if($user->verified)
                                <span class="badge bg-success">Verificado</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">No hay usuarios registrados</td>
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