@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h2>Añadir Tipo de Camión</h2>

    <form action="{{ route('tiposcamion.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('tiposcamion.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
