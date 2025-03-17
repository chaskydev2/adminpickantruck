<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pujas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Comentario</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pujas as $puja)
                                    <tr>
                                        <td>{{ $puja->usuario }}</td>
                                        <td>Bs. {{ number_format($puja->monto, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $puja->estado === 'aceptado' ? 'success' : ($puja->estado === 'rechazado' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($puja->estado) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($puja->created_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ $puja->comentario ?: 'Sin comentarios' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $pujas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
