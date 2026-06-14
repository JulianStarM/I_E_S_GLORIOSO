<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioMovimiento extends Model
{
    protected $table = 'inventario_movimientos';

    protected $fillable = [
        'id_libro',
        'tipo_movimiento',
        'cantidad',
        'cantidad_anterior',
        'cantidad_nueva',
        'referencia_tipo',
        'referencia_id',
        'id_usuario',
        'observaciones',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'cantidad_anterior' => 'integer',
            'cantidad_nueva' => 'integer',
        ];
    }

    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, 'id_libro');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Registra un movimiento de inventario.
     *
     * @param array{id_libro: int, tipo_movimiento: string, cantidad: int, referencia_tipo?: string, referencia_id?: int, id_usuario?: int, observaciones?: string} $datos
     */
    public static function registrar(array $datos): self
    {
        $libro = Libro::findOrFail($datos['id_libro']);

        return static::create([
            'id_libro' => $datos['id_libro'],
            'tipo_movimiento' => $datos['tipo_movimiento'],
            'cantidad' => $datos['cantidad'],
            'cantidad_anterior' => $libro->cantidad_disponible,
            'cantidad_nueva' => $libro->cantidad_disponible + $datos['cantidad'],
            'referencia_tipo' => $datos['referencia_tipo'] ?? null,
            'referencia_id' => $datos['referencia_id'] ?? null,
            'id_usuario' => $datos['id_usuario'] ?? auth()->id(),
            'observaciones' => $datos['observaciones'] ?? null,
        ]);
    }
}
