@extends('layouts.home')

@section('title', 'Detalle de Membresía #' . $membership->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('memberships.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
        <h2 class="mb-0">Membresía #{{ $membership->id }}</h2>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    {{-- Info box --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Datos de la Membresía</div>
            <div class="card-body">
                @php
                    $tierColors = ['pioneros' => 'success', 'visionarios' => 'primary', 'conservadores' => 'warning'];
                    $statusColors = ['pending' => 'warning text-dark', 'active' => 'success', 'cancelled' => 'danger'];
                    $statusLabels = ['pending' => 'Pendiente', 'active' => 'Activo', 'cancelled' => 'Cancelado'];
                    $methodLabels = ['qr' => 'QR', 'crypto' => 'Crypto', 'other' => 'Otro'];
                @endphp
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:45%">Plan</th>
                        <td>
                            <span class="badge bg-{{ $tierColors[$membership->tier] ?? 'secondary' }}">
                                {{ ucfirst($membership->tier) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Ciclo de Facturación</th>
                        <td>{{ $membership->billing_cycle === 'monthly' ? 'Mensual' : 'Anual' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Precio Pagado</th>
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
                    </tr>
                    <tr>
                        <th class="text-muted">Método de Pago</th>
                        <td>{{ $methodLabels[$membership->payment_method] ?? ($membership->payment_method ?? '—') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Estado</th>
                        <td>
                            <span class="badge bg-{{ $statusColors[$membership->status] ?? 'secondary' }}">
                                {{ $statusLabels[$membership->status] ?? $membership->status }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted">Fecha Inicio</th>
                        <td>{{ $membership->start_date ? $membership->start_date->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Fecha Fin</th>
                        <td>{{ $membership->end_date ? $membership->end_date->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Registrado</th>
                        <td>{{ $membership->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- User info --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">Usuario</div>
            <div class="card-body">
                @if ($membership->user)
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th class="text-muted" style="width:45%">Nombre</th>
                            <td>{{ $membership->user->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Email</th>
                            <td>{{ $membership->user->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Teléfono</th>
                            <td>{{ $membership->user->phone ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Empresa</th>
                            <td>{{ $membership->user->company_name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Rol</th>
                            <td>{{ ucfirst($membership->user->role ?? '—') }}</td>
                        </tr>
                    </table>

                    {{-- Botones de contacto --}}
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="mailto:{{ $membership->user->email }}"
                           class="btn btn-sm btn-outline-success">
                            <i class="fas fa-envelope me-1"></i> Enviar correo
                        </a>
                        @if ($membership->user->phone)
                            <a href="tel:{{ $membership->user->phone }}"
                               class="btn btn-sm btn-outline-info">
                                <i class="fas fa-phone me-1"></i> {{ $membership->user->phone }}
                            </a>
                        @endif
                        <a href="{{ route('users.show', $membership->user) }}"
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user me-1"></i> Ver perfil completo
                        </a>
                    </div>
                @else
                    <p class="text-muted">Usuario no disponible.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Payment proof --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Comprobante de Pago</div>
            <div class="card-body">
                @if ($membership->payment_proof_path)
                    @php
                        $path = $membership->payment_proof_path;
                        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $url = 'https://app.pickntruck.com/api/files/' . ltrim($path, '/');
                    @endphp
                    @if ($isImage)
                        <div class="d-flex flex-column align-items-center">
                            <img src="{{ $url }}"
                                 alt="Comprobante de pago"
                                 class="rounded shadow"
                                 style="max-height: 320px; max-width: 100%; width: auto; cursor: pointer;"
                                 onclick="window.open('{{ $url }}', '_blank')">
                            <div class="mt-3">
                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-external-link-alt me-1"></i> Abrir en pestaña nueva
                                </a>
                            </div>
                        </div>
                    @else
                        <p>
                            <i class="fas fa-file me-1"></i>
                            <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                Descargar comprobante ({{ strtoupper($ext) }})
                            </a>
                        </p>
                    @endif
                @else
                    <p class="text-muted mb-0">No se ha subido ningún comprobante de pago.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="mt-4 d-flex gap-2">
    @if ($membership->status === 'pending')
        <form action="{{ route('memberships.activate', $membership) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success"
                    onclick="return confirm('¿Activar esta membresía?')">
                <i class="fas fa-check me-1"></i> Activar Membresía
            </button>
        </form>
    @endif

    @if ($membership->status !== 'cancelled')
        <form action="{{ route('memberships.cancel', $membership) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger"
                    onclick="return confirm('¿Cancelar esta membresía?')">
                <i class="fas fa-times me-1"></i> Cancelar Membresía
            </button>
        </form>
    @endif
</div>
@endsection
