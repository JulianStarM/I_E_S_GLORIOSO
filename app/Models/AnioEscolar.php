<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnioEscolar extends Model
{
    protected $table = 'anios_escolares';

    protected $fillable = [
        'anio',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
        ];
    }

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'id_anio_escolar');
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class, 'id_anio_escolar');
    }

    public function devoluciones(): HasMany
    {
        return $this->hasMany(Devolucion::class, 'id_anio_escolar');
    }

    public function libros(): HasMany
    {
        return $this->hasMany(Libro::class, 'id_anio_escolar');
    }

    /**
     * Scope: Año escolar activo.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Obtiene el año escolar activo actual.
     */
    public static function actual(): ?self
    {
        return static::activo()->first();
    }
}
