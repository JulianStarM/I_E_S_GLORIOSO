<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PadreApoderado extends Model
{
    protected $table = 'padres_apoderados';

    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'telefono',
        'telefono_alt',
        'correo',
        'direccion',
        'ocupacion',
        'estado',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->apellidos}, {$this->nombres}";
    }

    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(Estudiante::class, 'estudiante_padre', 'id_padre', 'id_estudiante')
            ->withPivot('parentesco', 'es_principal')
            ->withTimestamps();
    }
}
