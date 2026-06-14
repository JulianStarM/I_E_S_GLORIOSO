<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleEntrega extends Model
{
    protected $table = 'detalle_entregas';

    protected $fillable = [
        'id_entrega',
        'id_libro',
        'cantidad',
        'estado_entrega',
        'numero_orden',
        'observaciones',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'numero_orden' => 'integer',
        ];
    }

    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class, 'id_entrega');
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function detalleDevolucion(): BelongsTo
    {
        return $this->belongsTo(DetalleDevolucion::class, 'id', 'id_detalle_entrega');
    }
}
