<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'requires_refrigeration',
        'hazardous',
        'active',
        'icon'
    ];

    protected $casts = [
        'requires_refrigeration' => 'boolean',
        'hazardous' => 'boolean',
        'active' => 'boolean',
    ];
}
