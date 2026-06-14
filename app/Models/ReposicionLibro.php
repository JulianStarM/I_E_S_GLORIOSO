<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReposicionLibro extends Model
{
    protected $table = 'reposiciones_libro';

    protected $fillable = [
        'id_estudiante',
        'id_libro',
        'id_devolucion',
        'id_detalle_devolucion',
        'id_usuario_registro',
        'motivo',
        'descripcion_motivo',
        'costo',
        'estado_pago',
        'fecha_limite_pago',
        'fecha_pago',
        'comprobante_pago',
        'observaciones',
        'fecha',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'costo' => 'decimal:2',
            'fecha' => 'date',
            'fecha_limite_pago' => 'date',
            'fecha_pago' => 'date',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function devolucion(): BelongsTo
    {
        return $this->belongsTo(Devolucion::class, 'id_devolucion');
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro');
    }
}
