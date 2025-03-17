<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'required_document_id', // Esta es la clave foránea correcta
        'file_path',
        'status',
        'admin_notes',
        'comments'
    ];

    /**
     * Obtiene el usuario al que pertenece este documento
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el tipo de documento requerido
     * Corregimos la relación para usar el nombre de columna correcto
     */
    public function requiredDocument()
    {
        return $this->belongsTo(RequiredDocument::class, 'required_document_id');
    }

    /**
     * Define los posibles estados de un documento
     */
    public static function getStatusOptions()
    {
        return [
            'pendiente' => 'Pendiente',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado'
        ];
    }
}
