@extends('layouts.home')

@section('title', 'Membresías')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Membresías</h2>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('memberships.index') }}" class="d-flex gap-2 mb-3 flex-wrap">
    <select name="status" class="form-select" style="max-width: 200px;">
        <option value="">Todos los estados</option>
        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pendiente</option>
        <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Activo</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
    </select>
    <select name="tier" class="form-select" style="max-width: 200px;">
        <option value="">Todos los planes</option>
        <option value="pioneros"      {{ request('tier') === 'pioneros'      ? 'selected' : '' }}>Pioneros</option>
        <option value="visionarios"   {{ request('tier') === 'visionarios'   ? 'selected' : '' }}>Visionarios</option>
        <option value="conservadores" {{ request('tier') === 'conservadores' ? 'selected' : '' }}>Conservadores</option>
    </select>
    <button type="submit" class="btn btn-primary">Filtrar</button>
    <a href="{{ route('memberships.index') }}" class="btn btn-outline-secondary">Limpiar</a>
</form>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Plan</th>
                        <th>Ciclo</th>
                        <th>Precio</th>
                        <th>Pago</th>
                        <th>Estado</th>
                        <th>Comprobante</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($memberships as $membership)
                    <tr>
                        <td class="fw-semibold">#{{ $membership->id }}</td>
                        <td>
                            @if ($membership->user)
                                <div>{{ $membership->user->name }}</div>
                                <small class="text-muted">{{ $membership->user->email }}</small>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $tierColors = ['pioneros' => 'success', 'visionarios' => 'primary', 'conservadores' => 'warning'];
                            @endphp
                            <span class="badge bg-{{ $tierColors[$membership->tier] ?? 'secondary' }}">
                                {{ ucfirst($membership->tier) }}
                            </span>
                        </td>
                        <td>{{ $membership->billing_cycle === 'monthly' ? 'Mensual' : 'Anual' }}</td>
                        <td>
                            @if ($membership->price_paid)
                                @php $cur = $membership->price_currency ?? 'USD'; @endphp
                                @if ($cur === 'BOB')
                                    {{ number_format($membership->price_paid, 0) }} BOB
                                @else
                                    ${{ number_format($membership->price_paid, 2) }} USD
                                @endif
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td>
                            @if ($membership->payment_method)
                                @php
                                    $methodLabels = ['qr' => 'QR', 'crypto' => 'Crypto', 'other' => 'Otro'];
                                @endphp
                                {{ $methodLabels[$membership->payment_method] ?? $membership->payment_method }}
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = ['pending' => 'warning text-dark', 'active' => 'success', 'cancelled' => 'danger'];
                                $statusLabels = ['pending' => 'Pendiente', 'active' => 'Activo', 'cancelled' => 'Cancelado'];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$membership->status] ?? 'secondary' }}">
                                {{ $statusLabels[$membership->status] ?? $membership->status }}
                            </span>
                        </td>
                        <td>
                            @if ($membership->payment_proof_path)
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-file-image me-1"></i> Sí
                                </span>
                            @else
                                <span class="text-muted">No</span>
                            @endif
                        </td>
                        <td>{{ $membership->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('memberships.show', $membership) }}" class="btn btn-sm btn-outline-info" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No hay membresías registradas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $memberships->links() }}
</div>
@endsection
