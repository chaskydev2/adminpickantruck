@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2>Editar Tipo de Camión</h2>

    <form action="{{ route('tiposcamion.update', $tiposcamion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name"
                   value="{{ $tiposcamion->name }}"
                   class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="description"
                      class="form-control"
                      required>{{ $tiposcamion->description }}</textarea>
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('tiposcamion.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
