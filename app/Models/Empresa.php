<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'user_id',
        'nombre',
        'logo',
        'descripcion',
        'telefono',
        'direccion',
        'sitio_web',
        'verificada'
    ];

    protected $casts = [
        'verificada' => 'boolean',
    ];

    /**
     * Obtiene el usuario propietario de la empresa.
     */
    /**
     * Obtiene el usuario propietario de la empresa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
