@extends('layouts.home')

@section('content')
<div style="width: 100%; padding: 20px;">
    <!-- Título del panel -->

    <div class="mb-4">
        <h1 style="font-size: 24px; font-weight: 600; color: #1f2937; margin: 0 0 10px 0;">Panel de Control</h1>
        <p style="font-size: 14px; color: #6b7280; margin: 0;">Resumen general de actividades y estadísticas</p>
    </div>

    <!-- Filtro de fechas -->
    <form action="{{ route('dashboard') }}" method="GET" style="margin-bottom: 20px;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div>
                <label for="start_date" style="font-weight: 500; color: #374151ff;">Fecha desde:</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" style="padding: 5px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div>
                <label for="end_date" style="font-weight: 500; color: #374151ff;">Fecha hasta:</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" style="padding: 5px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div>
                <button type="submit" style="background-color: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer;">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Sección de Resúmenes -->
    <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px;">
        <!-- Componente de Resumen de Actividades -->
        <div style="flex: 1; min-width: 300px; padding: 15px; box-sizing: border-box; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <x-dashboard.activity-summary 
                :total="$total" 
                :cargas="$cargas" 
                :rutas="$rutas" 
                :pujas="$pujas"
            />
        </div>

        <!-- Componente de Resumen de Usuarios -->
        <div style="flex: 1; min-width: 300px; padding: 15px; box-sizing: border-box; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <x-dashboard.user-summary 
                :totalusers="$totalUsuarios"
                :verificados="$usuariosVerificados"
                :pendientes="$usuariosPendientes"
                :nuevos="$usuariosNuevos"
            />
        </div>
    </div>

    <!-- Estadísticas de Pujas -->
<div style="margin: 20px 0; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); background-color: #ffffff;">

    <!-- Filtro de fechas dentro del card -->
    <form action="{{ route('dashboard') }}" method="GET" style="margin-bottom: 20px;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div>
                <label for="start_date" style="font-weight: 500; color: #374151;">Fecha desde:</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                    style="padding: 5px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div>
                <label for="end_date" style="font-weight: 500; color: #374151;">Fecha hasta:</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                    style="padding: 5px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div>
                <button type="submit"
                    style="background-color: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer;">
                    Filtrar
                </button>
            </div>
        </div>
    </form>

    <!-- Componente de estadísticas -->
    <x-dashboard.bids-stats :bidsStats="$bidsStats" />
</div>



    <!-- Filtro de fechas -->
    <form action="{{ route('dashboard') }}" method="GET" style="margin-bottom: 20px;">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <div>
                <label for="start_date" style="font-weight: 500; color: #374151ff;">Fecha desde:</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" style="padding: 5px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div>
                <label for="end_date" style="font-weight: 500; color: #374151ff;">Fecha hasta:</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" style="padding: 5px; border: 1px solid #d1d5db; border-radius: 4px;">
            </div>
            <div>
                <button type="submit" style="background-color: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer;">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Sección de Gráficos -->
    <div class="row" style="margin-bottom: 20px;">
        <!-- Gráfico de Pastel - Estados de Pujas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Distribución de Estados de Pujas</h5>
                </div>
                <div class="card-body">
                    <x-dashboard.bids-pie-chart :bidsStats="$bidsStats" />
                </div>
            </div>
        </div>

        <!-- Gráfico de Barras - Tipos de Carga y Rutas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Distribución por Tipo de Carga</h5>
                </div>
                <div class="card-body">
                    <x-dashboard.cargas-rutas-chart :chartData="$chartData" />
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Tablas -->
    <div class="row">
        <!-- Últimos Usuarios -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Últimos Usuarios Registrados</h5>
                </div>
                <div class="card-body p-0">
                    <x-dashboard.latest-users :latestUsers="$latestUsers" />
                </div>
            </div>
        </div>
        
        <!-- Últimas Pujas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Últimas Pujas</h5>
                </div>
                <div class="card-body p-0">
                    <x-dashboard.latest-bids :latestBids="$latestBids" />
                </div>
            </div>
        </div>
        
        <!-- Últimas Cargas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Últimas Cargas</h5>
                </div>
                <div class="card-body p-0">
                    <x-dashboard.latest-cargas :latestCargas="$latestCargas" />
                </div>
            </div>
        </div>
        
        <!-- Últimas Rutas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-bold">Últimas Rutas</h5>
                </div>
                <div class="card-body p-0">
                    <x-dashboard.latest-rutas :latestRutas="$latestRutas" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection