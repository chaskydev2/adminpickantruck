<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'required_document_id',
        'file_path',
        'status',
        'admin_notes',
        'comments'
    ];

    /**
     * Los accesores que deben agregarse a las formas de matriz/JSON.
     *
     * @var array
     */
    protected $appends = ['document_url'];

    /**
     * Obtiene el usuario al que pertenece este documento
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el tipo de documento requerido
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

    /**
     * Obtiene la URL completa del documento
     * Maneja automáticamente las URLs tanto en producción como en desarrollo local
     *
     * @return string|null
     */
    public function getDocumentUrlAttribute()
    {
        // Si no hay ruta de archivo, devolvemos null
        if (empty($this->file_path)) {
            return null;
        }
        
        // Si la ruta ya es una URL completa (http:// o https://), la devolvemos tal cual
        if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
            return $this->file_path;
        }
        
        // Si la ruta comienza con 'documents/' o 'storage/documents/', asumimos que es una ruta de almacenamiento
        if (str_starts_with($this->file_path, 'documents/') || str_starts_with($this->file_path, 'storage/documents/')) {
            return Storage::url($this->file_path);
        }
        
        // Si la ruta es relativa, asumimos que está en la carpeta documents del usuario
        return Storage::url('documents/' . $this->user_id . '/' . $this->file_path);
    }
}
