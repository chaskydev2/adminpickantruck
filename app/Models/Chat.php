<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'bid_id',
        'user_a_id',
        'user_b_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Obtiene la puja asociada a este chat.
     */
    public function bid(): BelongsTo
    {
        return $this->belongsTo(Bid::class);
    }

    /**
     * Obtiene todos los mensajes de este chat.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Obtiene los participantes del chat.
     * 
     * @return array
     */
    public function participants()
    {
        return [$this->bid->user, $this->bid->bideable->user];
    }

    /**
     * Obtiene el número de mensajes no leídos por un usuario específico.
     * 
     * @param int $userId
     * @return int
     */
    public function unreadCount($userId)
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('read', false)
            ->count();
    }

    /**
     * Marca todos los mensajes como leídos por un usuario específico.
     * 
     * @param int $userId
     * @return void
     */
    public function markAsRead($userId)
    {
        $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('read', false)
            ->update(['read' => true]);
    }

    /**
     * Obtiene el otro participante del chat.
     * 
     * @param int $currentUserId
     * @return \App\Models\User|null
     */
    public function getOtherParticipant($currentUserId)
    {
        $participants = $this->participants();
        return $participants[0]->id === $currentUserId ? $participants[1] : $participants[0];
    }
}
