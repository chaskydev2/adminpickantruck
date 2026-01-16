<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\OfertaCarga;
use App\Models\OfertaRuta;
use App\Models\User;
// UserDetail ha sido eliminado, usando solo User
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Obtener fechas del filtro
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Obtener el conteo de cargas (ofertas de carga) con filtro de fecha
        $totalCargas = OfertaCarga::when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
        
        // Obtener el conteo de rutas (ofertas de ruta) con filtro de fecha
        $totalRutas = OfertaRuta::when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
        
        // Obtener el conteo de pujas con filtro de fecha
        $totalPujas = Bid::when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
        
        // Calcular el total general de actividades
        $totalGeneral = $totalCargas + $totalRutas + $totalPujas;
        
        // Obtener estadísticas de usuarios con filtro de fecha
        $totalUsuarios = User::when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
            
        $usuariosVerificados = User::where('verified', 1)
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
            
        $usuariosPendientes = User::where('verified', 0)
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
            
        $usuariosNuevos = User::where('created_at', '>=', now()->subDays(30))
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
        
        // Obtener los últimos 5 usuarios registrados con sus documentos (con filtro de fecha)
        $latestUsers = User::with(['documents'])
            ->withCount('documents')
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener las últimas 5 pujas con sus relaciones (con filtro de fecha)
        $latestBids = Bid::with(['user', 'bideable'])
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener las últimas 5 cargas con sus relaciones (con filtro de fecha)
        $latestCargas = \App\Models\OfertaCarga::with(['user', 'cargoType'])
            ->withCount('bids as ofertas_recibidas')
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener las últimas 5 rutas con sus relaciones (con filtro de fecha)
        $latestRutas = \App\Models\OfertaRuta::with(['user', 'truckType'])
            ->withCount('bids as ofertas_recibidas')
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener estadísticas de pujas (con filtro de fecha)
        $pujasAceptadas = Bid::where('estado', 'aceptado')
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
            
        $pujasRechazadas = Bid::where('estado', 'rechazado')
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
            
        $pujasPendientes = Bid::where('estado', 'pendiente')
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
            
        $pujasTerminadas = Bid::whereIn('estado', ['terminado', 'completado'])
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->count();
        
        // Obtener datos para el gráfico de cargas por tipo de carga (con filtro de fecha)
        $cargasPorTipo = \App\Models\OfertaCarga::selectRaw('tipo_carga, COUNT(*) as total')
            ->with(['cargoType' => function($query) {
                $query->select('id', 'name');
            }])
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('tipo_carga')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'nombre' => $item->cargoType->name ?? 'Sin tipo',
                    'total' => $item->total
                ];
            });

        // Obtener datos para el gráfico de rutas por tipo de camión (con filtro de fecha)
        $rutasPorTipo = \App\Models\OfertaRuta::selectRaw('tipo_camion, COUNT(*) as total')
            ->with(['truckType' => function($query) {
                $query->select('id', 'name');
            }])
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('tipo_camion')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'nombre' => $item->truckType->name ?? 'Sin tipo',
                    'total' => $item->total
                ];
            });

        // Preparar datos para la vista
        $chartData = [
            'cargas' => $cargasPorTipo,
            'rutas' => $rutasPorTipo
        ];
        
        // Preparar estadísticas para la vista
        $bidsStats = [
            'total' => $totalPujas,
            'aceptadas' => $pujasAceptadas,
            'aceptado' => $pujasAceptadas, // Para el gráfico
            'rechazadas' => $pujasRechazadas,
            'rechazado' => $pujasRechazadas, // Para el gráfico
            'pendientes' => $pujasPendientes,
            'pendiente' => $pujasPendientes, // Para el gráfico
            'terminadas' => $pujasTerminadas,
            'terminado' => $pujasTerminadas // Para el gráfico
        ];

        return view('dashboard', [
            // Estadísticas de actividades
            'total' => $totalGeneral,
            'cargas' => $totalCargas,
            'rutas' => $totalRutas,
            'pujas' => $totalPujas,
            
            // Estadísticas de usuarios
            'totalUsuarios' => $totalUsuarios,
            'usuariosVerificados' => $usuariosVerificados,
            'usuariosPendientes' => $usuariosPendientes,
            'usuariosNuevos' => $usuariosNuevos,
            'latestUsers' => $latestUsers,
            'latestBids' => $latestBids,
            'latestCargas' => $latestCargas,
            'latestRutas' => $latestRutas,
            'bidsStats' => $bidsStats,
            'chartData' => $chartData
        ]);
    }
}