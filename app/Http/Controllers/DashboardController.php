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
    public function index()
    {
        // Obtener el conteo de cargas (ofertas de carga)
        $totalCargas = OfertaCarga::count();
        
        // Obtener el conteo de rutas (ofertas de ruta)
        $totalRutas = OfertaRuta::count();
        
        // Obtener el conteo de pujas
        $totalPujas = Bid::count();
        
        // Calcular el total general de actividades
        $totalGeneral = $totalCargas + $totalRutas + $totalPujas;
        
        // Obtener estadísticas de usuarios
        $totalUsuarios = User::count();
        $usuariosVerificados = User::where('verified', 1)->count();
        $usuariosPendientes = User::where('verified', 0)->count();
        $usuariosNuevos = User::where('created_at', '>=', now()->subDays(30))->count();
        
        // Obtener los últimos 5 usuarios registrados con sus documentos
        $latestUsers = User::with(['documents'])
            ->withCount('documents')
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener las últimas 5 pujas con sus relaciones
        $latestBids = Bid::with(['user', 'bideable'])
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener las últimas 5 cargas con sus relaciones
        $latestCargas = \App\Models\OfertaCarga::with(['user', 'cargoType'])
            ->withCount('bids as ofertas_recibidas')
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener las últimas 5 rutas con sus relaciones
        $latestRutas = \App\Models\OfertaRuta::with(['user', 'truckType'])
            ->withCount('bids as ofertas_recibidas')
            ->latest()
            ->take(5)
            ->get();
            
        // Obtener estadísticas de pujas
        $totalPujas = Bid::count();
        $pujasAceptadas = Bid::where('estado', 'aceptado')->count();
        $pujasRechazadas = Bid::where('estado', 'rechazado')->count();
        $pujasPendientes = Bid::where('estado', 'pendiente')->count();
        $pujasTerminadas = Bid::whereIn('estado', ['terminado', 'completado'])->count();
        
        // Obtener datos para el gráfico de cargas por tipo de carga
        $cargasPorTipo = \App\Models\OfertaCarga::selectRaw('tipo_carga, COUNT(*) as total')
            ->with(['cargoType' => function($query) {
                $query->select('id', 'name');
            }])
            ->groupBy('tipo_carga')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'nombre' => $item->cargoType->name ?? 'Sin tipo',
                    'total' => $item->total
                ];
            });

        // Obtener datos para el gráfico de rutas por tipo de camión
        $rutasPorTipo = \App\Models\OfertaRuta::selectRaw('tipo_camion, COUNT(*) as total')
            ->with(['truckType' => function($query) {
                $query->select('id', 'name');
            }])
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