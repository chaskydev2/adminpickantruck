@extends('layouts.home')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Cambiar Contraseña</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-text {
        font-size: 0.875em;
        color: #6c757d;
    }
</style>
@endpush
@endsection
