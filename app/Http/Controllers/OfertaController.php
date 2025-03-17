<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfertaController extends Controller
{
    public function cargas()
    {
        $ofertas = DB::table('ofertas_carga')
            ->join('users', 'users.id', '=', 'ofertas_carga.user_id')
            ->select('ofertas_carga.*', 'users.name as usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('ofertas.cargas', compact('ofertas'));
    }

    public function rutas()
    {
        $ofertas = DB::table('ofertas_ruta')
            ->join('users', 'users.id', '=', 'ofertas_ruta.user_id')
            ->select('ofertas_ruta.*', 'users.name as usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('ofertas.rutas', compact('ofertas'));
    }

    public function pujas()
    {
        $pujas = DB::table('bids')
            ->join('users', 'users.id', '=', 'bids.user_id')
            ->select('bids.*', 'users.name as usuario')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('ofertas.pujas', compact('pujas'));
    }
}
