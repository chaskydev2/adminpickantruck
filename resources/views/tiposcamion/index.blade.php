@extends('layouts.home')

@section('content')
<div class="container mt-4">
    <h1 class="mb-3">Tipos de Camiones</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('tiposcamion.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Añadir Tipo de Camión
    </a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($truckTypes as $index => $tipo)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $tipo->name }}</td>
                <td>{{ $tipo->description }}</td>
                <td>
                    <span class="badge {{ $tipo->active ? 'bg-success' : 'bg-danger' }}">
                        {{ $tipo->active ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('tiposcamion.edit', $tipo->id) }}" class="btn btn-sm btn-warning">
                        Editar
                    </a>

                    <form action="{{ route('tiposcamion.destroy', $tipo->id) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar este tipo de camión?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
