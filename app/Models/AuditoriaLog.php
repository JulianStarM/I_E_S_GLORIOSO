<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaLog extends Model
{
    protected $table = 'auditoria_logs';

    protected $fillable = [
        'id_usuario',
        'nombre_usuario',
        'accion',
        'modulo',
        'registro_id',
        'datos_anteriores',
        'datos_nuevos',
        'descripcion',
        'ip_address',
        'user_agent',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'datos_anteriores' => 'array',
            'datos_nuevos' => 'array',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Registra una acción de auditoría.
     *
     * @param array{accion: string, modulo: string, registro_id?: int, datos_anteriores?: array<string, mixed>, datos_nuevos?: array<string, mixed>, descripcion?: string} $datos
     */
    public static function registrar(array $datos): self
    {
        $usuario = auth()->user();

        return static::create([
            'id_usuario' => $usuario?->id,
            'nombre_usuario' => $usuario?->nombre ?? 'Sistema',
            'accion' => $datos['accion'],
            'modulo' => $datos['modulo'],
            'registro_id' => $datos['registro_id'] ?? null,
            'datos_anteriores' => isset($datos['datos_anteriores']) ? json_encode($datos['datos_anteriores']) : null,
            'datos_nuevos' => isset($datos['datos_nuevos']) ? json_encode($datos['datos_nuevos']) : null,
            'descripcion' => $datos['descripcion'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
