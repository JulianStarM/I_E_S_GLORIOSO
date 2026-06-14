<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Entrega extends Model
{
    protected $table = 'entregas';

    protected $fillable = [
        'codigo_general',
        'id_estudiante',
        'id_padre',
        'id_anio_escolar',
        'id_usuario_registro',
        'tipo_firmante',
        'fecha_entrega',
        'hora_entrega',
        'total_libros',
        'firma_estudiante',
        'firma_apoderado',
        'huella_digital',
        'constancia_pdf',
        'codigo_qr',
        'estado',
        'observaciones',
        'ip_registro',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'date',
            'total_libros' => 'integer',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(PadreApoderado::class, 'id_padre');
    }

    public function anioEscolar(): BelongsTo
    {
        return $this->belongsTo(AnioEscolar::class, 'id_anio_escolar');
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_registro');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleEntrega::class, 'id_entrega');
    }

    public function devolucion(): HasOne
    {
        return $this->hasOne(Devolucion::class, 'id_entrega');
    }

    /**
     * Scope: Entregas completadas.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope: Entregas sin devolución.
     *
     * @param \Illuminate\Database\Eloquent\Builder<self> $query
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeSinDevolucion($query)
    {
        return $query->whereDoesntHave('devolucion');
    }

    /**
     * Verifica si la entrega tiene devolución.
     */
    public function tieneDevolucion(): bool
    {
        return $this->devolucion()->exists();
    }

    /**
     * Genera un código general único.
     */
    public static function generarCodigo(int $anio): string
    {
        $ultimo = static::where('codigo_general', 'like', "ENT-{$anio}-%")
            ->orderByDesc('id')
            ->first();

        $secuencia = 1;
        if ($ultimo) {
            $partes = explode('-', $ultimo->codigo_general);
            $secuencia = (int) end($partes) + 1;
        }

        return sprintf('ENT-%d-%05d', $anio, $secuencia);
    }
}
