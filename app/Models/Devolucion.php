<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devolucion extends Model
{
    protected $table = 'devoluciones';

    protected $fillable = [
        'codigo_general',
        'id_entrega',
        'id_estudiante',
        'id_padre',
        'id_anio_escolar',
        'id_usuario_registro',
        'tipo_firmante',
        'fecha_devolucion',
        'hora_devolucion',
        'total_devueltos',
        'total_no_devueltos',
        'total_deficientes',
        'total_perdidos',
        'firma_estudiante',
        'firma_apoderado',
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
            'fecha_devolucion' => 'date',
            'total_devueltos' => 'integer',
            'total_no_devueltos' => 'integer',
            'total_deficientes' => 'integer',
            'total_perdidos' => 'integer',
        ];
    }

    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class, 'id_entrega');
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
        return $this->hasMany(DetalleDevolucion::class, 'id_devolucion');
    }

    /**
     * Genera un código general único para devoluciones.
     */
    public static function generarCodigo(int $anio): string
    {
        $ultimo = static::where('codigo_general', 'like', "DEV-{$anio}-%")
            ->orderByDesc('id')
            ->first();

        $secuencia = 1;
        if ($ultimo) {
            $partes = explode('-', $ultimo->codigo_general);
            $secuencia = (int) end($partes) + 1;
        }

        return sprintf('DEV-%d-%05d', $anio, $secuencia);
    }
}
