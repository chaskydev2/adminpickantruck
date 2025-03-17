<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckType extends Model
{
    use HasFactory;

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
}
