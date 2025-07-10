@extends('layouts.home')

@section('title', 'Detalles del Usuario - Vista Mínima')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <h2 class="mb-0 me-4">Detalles del Usuario</h2>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-soft-primary text-primary rounded-circle font-size-24">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <h4 class="mb-1">{{ $user->name ?? 'Nombre del Usuario' }}</h4>
                <p class="text-muted">{{ $user->email ?? 'correo@ejemplo.com' }}</p>
            </div>

            <div class="table-responsive">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th class="ps-0" style="width: 40%;">ID:</th>
                            <td class="text-muted">#{{ $user->id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Rol:</th>
                            <td class="text-muted">{{ $user->role ?? 'Usuario Estándar' }}</td>
                        </tr>
                        <tr>
                            <th class="ps-0">Estado:</th>
                            <td>
                                @if(isset($user->verified) && $user->verified)
                                    <span class="badge bg-success">Verificado</span>
                                @else
                                    <span class="badge bg-warning text-dark">No Verificado</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-0">Fecha de Registro:</th>
                            <td class="text-muted">
                                {{ isset($user->created_at) ? $user->created_at->format('d/m/Y') : 'N/A' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-lg {
    height: 5rem;
    width: 5rem;
    line-height: 5rem;
    font-size: 2rem;
    text-align: center;
    border-radius: 50%;
}
</style>
@endpush
