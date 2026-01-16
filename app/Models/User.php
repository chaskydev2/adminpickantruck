<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\OfertaRuta;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the ofertas de carga for the user.
     */
    /**
     * Obtiene la empresa asociada al usuario.
     */
    public function empresa()
    {
        return $this->hasOne(Empresa::class);
    }

    /**
     * Obtiene las ofertas de carga del usuario.
     */
    public function ofertasCarga()
    {
        return $this->hasMany(OfertaCarga::class);
    }

        /**
     * Obtiene las ofertas de ruta del usuario.
     */
    public function ofertasRuta()
    {
        return $this->hasMany(OfertaRuta::class, 'user_id');
    }
    
    /**
     * Obtiene los documentos subidos por el usuario.
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class, 'user_id');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'verified',
        'estado',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'verified' => 'boolean',
            'estado' => 'string',
            'last_login_at' => 'datetime',
        ];
    }
}
