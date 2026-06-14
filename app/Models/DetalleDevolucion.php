<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleDevolucion extends Model
{
    protected $table = 'detalle_devoluciones';

    protected $fillable = [
        'id_devolucion',
        'id_libro',
        'id_detalle_entrega',
        'estado_libro',
        'observaciones',
        'foto_evidencia',
    ];

    public function devolucion(): BelongsTo
    {
        return $this->belongsTo(Devolucion::class, 'id_devolucion');
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function detalleEntrega(): BelongsTo
    {
        return $this->belongsTo(DetalleEntrega::class, 'id_detalle_entrega');
    }
}
