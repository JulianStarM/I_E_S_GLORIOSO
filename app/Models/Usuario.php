<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'id_institucion',
        'nombre',
        'email',
        'password',
        'rol',
        'estado',
    ];

    protected $hidden = [
        'password',
        'token_remember',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'correo_verificado_en' => 'datetime',
            'ultimo_acceso' => 'datetime',
            'password' => 'hashed',
            'estado' => 'boolean',
        ];
    }

    /**
     * Get the name of the unique identifier for the user (for auth).
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * Get the password field name.
     */
    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    /**
     * Get the remember token field name.
     */
    public function getRememberTokenName(): string
    {
        return 'token_remember';
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion');
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class, 'id_usuario_registro');
    }

    public function devoluciones(): HasMany
    {
        return $this->hasMany(Devolucion::class, 'id_usuario_registro');
    }

    public function auditoriaLogs(): HasMany
    {
        return $this->hasMany(AuditoriaLog::class, 'id_usuario');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }

    public function notificacionesSinLeer(): HasMany
    {
        return $this->notificaciones()->where('leida', false);
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     */
    public function tieneRol(string $rol): bool
    {
        return $this->rol === $rol;
    }

    /**
     * Verifica si es administrador.
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Verifica si la cuenta está activa.
     */
    public function estaActivo(): bool
    {
        return (bool) $this->estado;
    }
}
