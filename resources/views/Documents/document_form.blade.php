@extends('layouts.home')

@section('title', isset($document) ? 'Editar Documento' : 'Nuevo Documento')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <h2 class="mb-0">
                <i class="fas fa-file-alt text-primary me-2"></i>
                {{ isset($document) ? 'Editar Documento' : 'Nuevo Documento' }}
            </h2>
        </div>
        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Volver al listado
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-dark">
                <div class="card-body">
                    <form action="{{ isset($document) ? route('documents.update', $document) : route('documents.store') }}" method="POST">
                        @csrf
                        @if(isset($document))
                            @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Documento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $document->name ?? '') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" 
                                      rows="3">{{ old('description', $document->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas Internas</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" 
                                      rows="2">{{ old('notes', $document->notes ?? '') }}</textarea>
                            <div class="form-text">Esta información solo es visible para administradores.</div>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       id="active" name="active" value="1"
                                       @if(old('active', isset($document) ? $document->active : true)) checked @endif>
                                <label class="form-check-label" for="active">Documento activo</label>
                            </div>
                            <div class="form-text">Los documentos inactivos no estarán disponibles para los usuarios.</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                @if(isset($document) && $document->userDocuments->count() > 0)
                                    <div class="alert alert-info py-2 mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Este documento tiene {{ $document->userDocuments->count() }} registros asociados.
                                    </div>
                                @endif
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    {{ isset($document) ? 'Actualizar' : 'Guardar' }} Documento
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
