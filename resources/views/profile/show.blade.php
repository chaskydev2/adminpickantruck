@extends('layouts.home')

@section('title', 'Mi Perfil')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Mi Perfil</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="avatar-circle mb-3">
                            <span class="initials">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                        <h4>{{ Auth::user()->name }}</h4>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Miembro desde</span>
                            <span>{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Editar Perfil
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-key me-1"></i> Cambiar Contraseña
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .avatar-circle {
        width: 100px;
        height: 100px;
        background-color: #0d6efd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .initials {
        color: white;
        font-size: 40px;
        font-weight: bold;
    }
    
    .list-group-item {
        padding: 0.75rem 1.25rem;
    }
</style>
@endpush
@endsection
