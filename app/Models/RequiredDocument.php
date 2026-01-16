<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'notes',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * Obtiene todos los documentos de usuario asociados a este documento requerido
     */
    public function userDocuments()
    {
        // Corregimos la clave foránea de 'document_id' a 'required_document_id'
        return $this->hasMany(UserDocument::class, 'required_document_id');
    }
}
