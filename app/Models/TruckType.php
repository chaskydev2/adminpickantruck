<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TruckType extends Model
{
    use HasFactory;

    protected $table = 'truck_types';

    protected $fillable = [
        'name',
        'description',
        'max_weight',
        'length',
        'width',
        'height',
        'active',
        'icon'
    ];

    protected $casts = [
        'max_weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float',
        'active' => 'boolean',
    ];

    /**
     * Get all of the ofertasRuta for the TruckType
     */
    public function ofertasRuta(): HasMany
    {
        return $this->hasMany(OfertaRuta::class, 'tipo_camion');
    }
}
