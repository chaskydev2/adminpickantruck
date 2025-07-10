<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'chat_id',
        'user_id',
        'content',
        'read'
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array
     */
    protected $casts = [
        'read' => 'boolean',
    ];

    /**
     * Obtiene el chat al que pertenece el mensaje.
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Obtiene el usuario que envió el mensaje.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
