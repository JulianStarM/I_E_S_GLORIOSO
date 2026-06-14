<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Libro extends Model
{
    protected $table = 'libros';

    protected $fillable = [
        'codigo_libro',
        'isbn',
        'nombre',
        'tipo_material',
        'area',
        'grado',
        'nivel',
        'editorial',
        'anio_edicion',
        'anio_escolar',
        'id_anio_escolar',
        'cantidad_total',
        'cantidad_disponible',
        'cantidad_entregada',
        'cantidad_devuelta',
        'cantidad_perdida',
        'cantidad_deficiente',
        'estado',
        'imagen_portada',
        'codigo_qr',
        'observaciones',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'grado' => 'integer',
            'cantidad_total' => 'integer',
            'cantidad_disponible' => 'integer',
            'cantidad_entregada' => 'integer',
            'cantidad_devuelta' => 'integer',
            'cantidad_perdida' => 'integer',
            'cantidad_deficiente' => 'integer',
        ];
    }

    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class, 'id_anio_escolar');
    }

    public function detalleEntregas(): HasMany
    {
        return $this->hasMany(DetalleEntrega::class, 'id_libro');
    }

    public function detalleDevoluciones(): HasMany
    {
        return $this->hasMany(DetalleDevolucion::class, 'id_libro');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(InventarioMovimiento::class, 'id_libro');
    }

    public function reposiciones(): HasMany
    {
        return $this->hasMany(ReposicionLibro::class, 'id_libro');
    }

    /**
     * Scope: Libros activos.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope: Con stock disponible.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeConStock($query)
    {
        return $query->where('cantidad_disponible', '>', 0);
    }

    /**
     * Scope: Filtrar por grado.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeDeGrado($query, int $grado)
    {
        return $query->where('grado', $grado);
    }

    /**
     * Scope: Filtrar por nivel.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeDeNivel($query, string $nivel)
    {
        return $query->where('nivel', $nivel);
    }

    /**
     * Verifica si hay stock disponible.
     */
    public function tieneStock(int $cantidad = 1): bool
    {
        return $this->cantidad_disponible >= $cantidad;
    }

    /**
     * Porcentaje de libros entregados.
     */
    public function getPorcentajeEntregadoAttribute(): float
    {
        if ($this->cantidad_total === 0) {
            return 0;
        }

        return round(($this->cantidad_entregada / $this->cantidad_total) * 100, 2);
    }
}
