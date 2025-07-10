<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OfertaCarga extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ofertas_carga';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['ofertas_recibidas'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tipo_carga',
        'origen',
        'destino',
        'peso',
        'fecha_inicio',
        'presupuesto'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'peso' => 'float',
        'presupuesto' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the carga.
     */
    /**
     * Obtiene el usuario propietario de la oferta de carga.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene todas las pujas asociadas a esta oferta de carga.
     */
    public function bids()
    {
        return $this->morphMany(Bid::class, 'bideable');
    }

    /**
     * Obtiene el número de ofertas recibidas para esta carga.
     *
     * @return int
     */
    public function getOfertasRecibidasAttribute()
    {
        return $this->bids()->count();
    }

    /**
     * Get the cargo type for the oferta.
     */
    public function cargoType()
    {
        return $this->belongsTo(CargoType::class, 'tipo_carga');
    }

    /**
     * Get the tipo carga as text.
     *
     * @return string
     */
    public function getTipoCargaTextAttribute()
    {
        return $this->cargoType ? $this->cargoType->name : 'Sin tipo';
    }

    /**
     * Format the peso with 2 decimal places and add 'kg'.
     *
     * @return string
     */
    public function getPesoFormateadoAttribute()
    {
        return number_format($this->peso, 2) . ' kg';
    }

    /**
     * Format the presupuesto with 2 decimal places and add 'USD'.
     *
     * @return string
     */
    public function getPresupuestoFormateadoAttribute()
    {
        return '$' . number_format($this->presupuesto, 2);
    }

    /**
     * Format the fecha_inicio as 'd/m/Y'.
     *
     * @return string
     */
    public function getFechaFormateadaAttribute()
    {
        return $this->fecha_inicio ? $this->fecha_inicio->format('d/m/Y') : 'No definida';
    }
}
