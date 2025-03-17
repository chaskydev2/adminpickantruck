<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Publicaciones de Ruta') }}
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
                                    <th>Tipo de Camión</th>
                                    <th>Origen</th>
                                    <th>Destino</th>
                                    <th>Capacidad</th>
                                    <th>Precio Referencial</th>
                                    <th>Fecha Inicio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ofertas as $oferta)
                                    <tr>
                                        <td>{{ $oferta->usuario }}</td>
                                        <td>{{ $oferta->tipo_camion }}</td>
                                        <td>{{ $oferta->origen }}</td>
                                        <td>{{ $oferta->destino }}</td>
                                        <td>{{ $oferta->capacidad }} kg</td>
                                        <td>Bs. {{ number_format($oferta->precio_referencial, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($oferta->fecha_inicio)->format('d/m/Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $ofertas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
