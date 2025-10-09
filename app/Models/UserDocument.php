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
        
        // IMPORTANTE: Los documentos están guardados en el servidor principal (pickandtruckfinal)
        // que corre en http://localhost:8000 y guarda los archivos en public/documents
        // La ruta guardada en BD es relativa: "documents/{userId}/{filename}"
        
        // URL del servidor principal donde están los archivos
        $mainAppUrl = env('MAIN_APP_URL', 'http://localhost:8000');
        
        // Si la ruta comienza con 'documents/', construir URL completa al servidor principal
        if (str_starts_with($this->file_path, 'documents/')) {
            return $mainAppUrl . '/' . $this->file_path;
        }
        
        // Si la ruta comienza con 'storage/documents/', ajustar
        if (str_starts_with($this->file_path, 'storage/documents/')) {
            // Remover 'storage/' porque en pickandtruckfinal está en public/documents
            $relativePath = str_replace('storage/', '', $this->file_path);
            return $mainAppUrl . '/' . $relativePath;
        }
        
        // Si la ruta es relativa, asumimos que está en la carpeta documents del usuario
        return $mainAppUrl . '/documents/' . $this->user_id . '/' . $this->file_path;
    }
}
