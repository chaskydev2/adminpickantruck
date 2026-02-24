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
     * Soporta múltiples ubicaciones posibles de documentos
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
        
        // IMPORTANTE: Los documentos están guardados en el servidor principal (app.pickntruck.com)
        // Los archivos pueden estar en diferentes ubicaciones debido a migraciones:
        // - /public_html/documents/{userId}/
        // - /public_html/public/documents/{userId}/
        // - /public_html/storage/app/public/documents/{userId}/
        
        // URL del servidor principal donde están los archivos
        $mainAppUrl = env('MAIN_APP_URL', 'http://localhost:8000');
        
        // Extraer nombre del archivo de la ruta
        $fileName = basename($this->file_path);
        $userId = $this->user_id;
        
        // La aplicación principal está en app.pickntruck.com
        // Los archivos físicos están en /public_html/public/documents/{userId}/
        // Pero las URLs web son https://app.pickntruck.com/documents/{userId}/
        
        // Si la ruta ya comienza con alguna de las ubicaciones conocidas, usarla directamente
        if (str_starts_with($this->file_path, 'documents/')) {
            return $mainAppUrl . '/' . $this->file_path;
        }
        
        // Si comienza con 'public/documents/', remover el prefijo 'public/'
        if (str_starts_with($this->file_path, 'public/documents/')) {
            $relativePath = str_replace('public/', '', $this->file_path);
            return $mainAppUrl . '/' . $relativePath;
        }
        
        // Si comienza con 'storage/documents/', cambiar a documents/
        if (str_starts_with($this->file_path, 'storage/documents/')) {
            $relativePath = str_replace('storage/', '', $this->file_path);
            return $mainAppUrl . '/' . $relativePath;
        }
        
        // Por defecto, construir URL estándar
        return $mainAppUrl . '/documents/' . $userId . '/' . $fileName;
    }
}
