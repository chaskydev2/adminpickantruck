<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bid extends Model
{
    use HasFactory;

    protected $table = 'bids';
    
    protected $fillable = [
        'user_id',
        'bideable_id',
        'bideable_type',
        'monto',
        'fecha_hora',
        'comentario',
        'estado',
        'confirmacion_usuario_a',
        'confirmacion_usuario_b',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'monto' => 'decimal:2',
        'confirmacion_usuario_a' => 'boolean',
        'confirmacion_usuario_b' => 'boolean',
    ];

    // Relación con el usuario que hizo la puja
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica con Ruta o Carga
    public function bideable()
    {
        return $this->morphTo();
    }

    // Relación con el chat (si existe)
    public function chat()
    {
        return $this->hasOne(Chat::class, 'bid_id');
    }

    // Accesor para el tipo de objeto (OfertaRuta o OfertaCarga)
    public function getTipoObjetoAttribute()
    {
        if ($this->bideable_type === 'App\\Models\\OfertaRuta') {
            return 'Ruta';
        } elseif ($this->bideable_type === 'App\\Models\\OfertaCarga') {
            return 'Carga';
        }
        return 'Desconocido';
    }

    // Accesor para el estado formateado
    public function getEstadoFormateadoAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->estado));
    }

    // Método para obtener los datos del objeto relacionado
    public function obtenerObjetoRelacionado()
    {
        return $this->bideable()->withTrashed()->first();
    }

    // Método para obtener el propietario del objeto
    public function propietario()
    {
        $objeto = $this->obtenerObjetoRelacionado();
        return $objeto ? $objeto->user : null;
    }
}
