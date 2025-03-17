<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDocument;
use App\Models\RequiredDocument;
use App\Models\TruckType;
use App\Models\CargoType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el periodo de filtrado
        $periodo = $request->input('periodo', 'all');
        
        // Definir la fecha de inicio según el periodo seleccionado
        $fechaInicio = null;
        $periodoTexto = 'General';
        
        switch ($periodo) {
            case 'today':
                $fechaInicio = Carbon::today();
                $periodoTexto = 'Hoy';
                break;
            case 'week':
                $fechaInicio = Carbon::now()->subDays(7);
                $periodoTexto = 'Últimos 7 días';
                break;
            case 'month':
                $fechaInicio = Carbon::now()->subDays(30);
                $periodoTexto = 'Últimos 30 días';
                break;
            case 'year':
                $fechaInicio = Carbon::now()->subYear();
                $periodoTexto = 'Último año';
                break;
            default:
                $fechaInicio = null;
                $periodoTexto = 'General';
        }

        // Construir consultas con filtro de fecha si aplica
        $ofertasCargaQuery = DB::table('ofertas_carga');
        $ofertasRutaQuery = DB::table('ofertas_ruta');
        $bidsQuery = DB::table('bids');
        $usersQuery = User::query();
        
        if ($fechaInicio) {
            $ofertasCargaQuery->where('created_at', '>=', $fechaInicio);
            $ofertasRutaQuery->where('created_at', '>=', $fechaInicio);
            $bidsQuery->where('created_at', '>=', $fechaInicio);
            $usersQuery->where('created_at', '>=', $fechaInicio);
        }

        // Estadísticas avanzadas de pujas
        $totalBids = $bidsQuery->count();
        $acceptedBids = (clone $bidsQuery)->where('estado', 'aceptado')->count();
        $completedBids = (clone $bidsQuery)->where('estado', 'terminado')->count();
        $pendingBids = (clone $bidsQuery)->where('estado', 'pendiente')->count();
        $rejectedBids = (clone $bidsQuery)->where('estado', 'rechazado')->count();
        
        // Tasas y porcentajes
        $totalOfertas = $ofertasCargaQuery->count() + $ofertasRutaQuery->count();
        $acceptanceRate = $totalBids > 0 ? round(($acceptedBids / $totalBids) * 100, 1) : 0;
        // Eliminamos la tasa de completitud
        // $completionRate = $acceptedBids > 0 ? round(($completedBids / $acceptedBids) * 100, 1) : 0;

        // Obtener las últimas actividades para mostrar en tablas
        $ultimasCargas = DB::table('ofertas_carga')
            ->join('users', 'users.id', '=', 'ofertas_carga.user_id')
            ->select(
                'ofertas_carga.id',
                'ofertas_carga.origen',
                'ofertas_carga.destino',
                'ofertas_carga.tipo_carga',
                'ofertas_carga.presupuesto',
                'ofertas_carga.fecha_inicio',
                'ofertas_carga.created_at',
                'users.name as usuario'
            )
            ->orderBy('ofertas_carga.created_at', 'desc')
            ->take(5)
            ->get();
            
        $ultimasRutas = DB::table('ofertas_ruta')
            ->join('users', 'users.id', '=', 'ofertas_ruta.user_id')
            ->select(
                'ofertas_ruta.id',
                'ofertas_ruta.origen',
                'ofertas_ruta.destino',
                'ofertas_ruta.tipo_camion',
                'ofertas_ruta.precio_referencial',
                'ofertas_ruta.fecha_inicio',
                'ofertas_ruta.created_at',
                'users.name as usuario'
            )
            ->orderBy('ofertas_ruta.created_at', 'desc')
            ->take(5)
            ->get();
            
        $ultimasPujas = DB::table('bids')
            ->join('users', 'users.id', '=', 'bids.user_id')
            ->select(
                'bids.id',
                'bids.monto',
                'bids.estado',
                'bids.comentario',
                'bids.created_at',
                'users.name as usuario'
            )
            ->orderBy('bids.created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'periodo' => [
                'texto' => $periodoTexto,
                'valor' => $periodo
            ],
            'usuarios' => [
                'total' => User::count(),
                'nuevos' => $usersQuery->count(),
                'verificados' => User::whereNotNull('email_verified_at')->count(),
                'pendientes' => User::whereNull('email_verified_at')->count()
            ],
            'actividad' => [
                'cargas' => $ofertasCargaQuery->count(),
                'rutas' => $ofertasRutaQuery->count(),
                'pujas' => [
                    'total' => $totalBids,
                    'aceptadas' => $acceptedBids,
                    'terminadas' => $completedBids,
                    'pendientes' => $pendingBids,
                    'rechazadas' => $rejectedBids
                ],
                'tasas' => [
                    'aceptacion' => $acceptanceRate,
                    // Eliminamos la tasa de completitud
                    // 'completitud' => $completionRate
                ],
                'graficas' => [
                    'distribucion_pujas' => [
                        'aceptadas' => $acceptedBids,
                        'pendientes' => $pendingBids,
                        'rechazadas' => $rejectedBids,
                        'terminadas' => $completedBids
                    ],
                    'distribucion_ofertas' => [
                        'cargas' => $ofertasCargaQuery->count(),
                        'rutas' => $ofertasRutaQuery->count()
                    ]
                ]
            ],
            'ultimas_actividades' => [
                'cargas' => $ultimasCargas,
                'rutas' => $ultimasRutas,
                'pujas' => $ultimasPujas
            ],
            'ultimos_usuarios' => User::latest()->take(5)->get()
        ];

        return view('dashboard', compact('stats'));
    }
}
