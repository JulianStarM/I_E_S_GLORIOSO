<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institucion extends Model
{
    protected $table = 'instituciones';

    protected $fillable = [
        'codigo_modular',
        'nombre',
        'nivel',
        'ugel',
        'direccion',
        'distrito',
        'provincia',
        'region',
        'telefono',
        'correo',
        'director',
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

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_institucion');
    }

    public function estudiantes(): HasMany
    {
        return $this->hasMany(Estudiante::class, 'id_institucion');
    }
}
