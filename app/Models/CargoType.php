<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];
    
    // No necesitamos $casts ya que no hay campos booleanos en la base de datos
}
