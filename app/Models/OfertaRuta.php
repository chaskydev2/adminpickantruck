<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OfertaRuta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ofertas_ruta';

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
        'tipo_camion',
        'origen',
        'destino',
        'fecha_inicio',
        'capacidad',
        'precio_referencial',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'capacidad' => 'decimal:2',
        'precio_referencial' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the offer.
     */
    /**
     * Obtiene el usuario propietario de la oferta de ruta.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene todas las pujas asociadas a esta oferta de ruta.
     */
    public function bids()
    {
        return $this->morphMany(Bid::class, 'bideable');
    }

    /**
     * Obtiene el número de ofertas recibidas para esta ruta.
     *
     * @return int
     */
    public function getOfertasRecibidasAttribute()
    {
        return $this->bids()->count();
    }

    /**
     * Get the truck type for the offer.
     */
    public function truckType(): BelongsTo
    {
        return $this->belongsTo(TruckType::class, 'tipo_camion');
    }

    /**
     * Format the price with 2 decimal places and add 'USD'.
     *
     * @return string
     */
    public function getPrecioFormateadoAttribute()
    {
        return '$' . number_format($this->precio_referencial, 2);
    }
}
