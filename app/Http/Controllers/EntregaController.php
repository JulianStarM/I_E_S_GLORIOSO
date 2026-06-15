<?php

namespace App\Http\Controllers;

use App\Models\AnioEscolar;
use App\Models\AuditoriaLog;
use App\Models\DetalleEntrega;
use App\Models\Entrega;
use App\Models\Estudiante;
use App\Models\InventarioMovimiento;
use App\Models\Libro;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EntregaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Entrega::with(['estudiante', 'anioEscolar', 'usuarioRegistro']);
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo_general', 'like', "%{$buscar}%")
                    ->orWhereHas('estudiante', fn ($sq) => $sq->where('apellidos', 'like', "%{$buscar}%")
                        ->orWhere('nombres', 'like', "%{$buscar}%")
                        ->orWhere('dni', 'like', "%{$buscar}%"));
            });
        }
        if ($request->filled('estado')) { $query->where('estado', $request->input('estado')); }
        if ($request->filled('fecha_desde')) { $query->where('fecha_entrega', '>=', $request->input('fecha_desde')); }
        if ($request->filled('fecha_hasta')) { $query->where('fecha_entrega', '<=', $request->input('fecha_hasta')); }

        $entregas = $query->orderByDesc('fecha_entrega')->orderByDesc('id')->paginate(20)->withQueryString();
        return view('entregas.index', compact('entregas'));
    }

    public function create(): View
    {
        $anioActual = AnioEscolar::actual();
        $estudiantes = Estudiante::activos()
            ->when($anioActual, fn ($q) => $q->where('id_anio_escolar', $anioActual->id))
            ->orderBy('apellidos')->get();
        $libros = Libro::activos()->conStock()
            ->when($anioActual, fn ($q) => $q->where('id_anio_escolar', $anioActual->id))
            ->orderBy('grado')->orderBy('area')->get();
        return view('entregas.create', compact('estudiantes', 'libros', 'anioActual'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_estudiante' => ['required', 'exists:estudiantes,id'],
            'tipo_firmante' => ['required', 'in:estudiante,apoderado'],
            'apoderado_dni' => ['required_if:tipo_firmante,apoderado', 'nullable', 'string', 'max:15'],
            'apoderado_nombres' => ['required_if:tipo_firmante,apoderado', 'nullable', 'string', 'max:255'],
            'apoderado_apellidos' => ['required_if:tipo_firmante,apoderado', 'nullable', 'string', 'max:255'],
            'apoderado_parentesco' => ['required_if:tipo_firmante,apoderado', 'nullable', 'string', 'max:100'],
            'apoderado_telefono' => ['nullable', 'string', 'max:20'],
            'libros' => ['required', 'array', 'min:1'],
            'libros.*' => ['required', 'exists:libros,id'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $anioActual = AnioEscolar::actual();
        if (! $anioActual) {
            return back()->with('error', 'No hay un año escolar activo.')->withInput();
        }
        
        $estudiante = Estudiante::findOrFail($request->input('id_estudiante'));
        
        // Verificar si ya tiene entrega en el anio actual
        $entregaExistente = Entrega::where('id_estudiante', $estudiante->id)
            ->where('id_anio_escolar', $anioActual->id)
            ->where('estado', 'completada')
            ->first();
            
        if ($entregaExistente) {
            return back()->with('error', 'El estudiante ya tiene una entrega registrada ('.$entregaExistente->codigo_general.').')->withInput();
        }

        DB::beginTransaction();
        try {
            $idPadre = null;
            if ($request->input('tipo_firmante') === 'apoderado') {
                $padre = \App\Models\PadreApoderado::firstOrCreate(
                    ['dni' => $request->input('apoderado_dni')],
                    [
                        'nombres' => $request->input('apoderado_nombres'),
                        'apellidos' => $request->input('apoderado_apellidos'),
                        'telefono' => $request->input('apoderado_telefono'),
                    ]
                );
                $idPadre = $padre->id;
                
                // Relacionar si no existe
                if (!$estudiante->padres()->where('id_padre', $padre->id)->exists()) {
                    $estudiante->padres()->attach($padre->id, [
                        'parentesco' => $request->input('apoderado_parentesco'),
                        'es_principal' => true
                    ]);
                }
            }

            $entrega = Entrega::create([
                'codigo_general' => Entrega::generarCodigo($anioActual->anio),
                'id_estudiante' => $estudiante->id,
                'id_padre' => $idPadre,
                'id_anio_escolar' => $anioActual->id,
                'id_usuario_registro' => auth()->id(),
                'tipo_firmante' => $request->input('tipo_firmante'),
                'fecha_entrega' => now()->toDateString(),
                'hora_entrega' => now()->toTimeString(),
                'total_libros' => 0,
                'estado' => 'completada',
                'observaciones' => $request->input('observaciones'),
                'ip_registro' => $request->ip(),
            ]);

            $totalLibros = 0;
            $orden = 1;
            foreach ($request->input('libros') as $idLibro) {
                $libro = Libro::findOrFail($idLibro);
                $cantidad = 1; // 1 libro de cada materia

                if (! $libro->tieneStock($cantidad)) {
                    DB::rollBack();
                    return back()->with('error', "Sin stock suficiente para: {$libro->nombre}")->withInput();
                }

                DetalleEntrega::create([
                    'id_entrega' => $entrega->id,
                    'id_libro' => $libro->id,
                    'cantidad' => $cantidad,
                    'entregas' => 'bueno',
                    'numero_orden' => $orden++,
                ]);

                $libro->decrement('cantidad_disponible', $cantidad);
                $libro->increment('cantidad_entregada', $cantidad);

                InventarioMovimiento::create([
                    'id_libro' => $libro->id, 'tipo_movimiento' => 'entrega',
                    'cantidad' => -$cantidad,
                    'cantidad_anterior' => $libro->cantidad_disponible + $cantidad,
                    'cantidad_nueva' => $libro->cantidad_disponible,
                    'referencia_tipo' => 'entrega', 'referencia_id' => $entrega->id,
                    'id_usuario' => auth()->id(),
                ]);

                $totalLibros += $cantidad;
            }

            $entrega->update(['total_libros' => $totalLibros]);
            DB::commit();

            AuditoriaLog::registrar([
                'accion' => 'crear', 'modulo' => 'entregas', 'registro_id' => $entrega->id,
                'descripcion' => "Entrega {$entrega->codigo_general}: {$totalLibros} libros",
            ]);

            return redirect()->route('entregas.show', $entrega)
                ->with('success', "Entrega {$entrega->codigo_general} registrada ({$totalLibros} libros).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar entrega: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Entrega $entrega): View
    {
        $entrega->load(['estudiante', 'anioEscolar', 'usuarioRegistro', 'detalles.libro', 'devolucion']);
        return view('entregas.show', compact('entrega'));
    }

    /**
     * API: Buscar estudiantes (para select dinámico).
     */
    public function buscarEstudiantes(Request $request): JsonResponse
    {
        $buscar = $request->input('q', '');
        $estudiantes = Estudiante::activos()
            ->where(function ($q) use ($buscar) {
                $q->where('apellidos', 'like', "%{$buscar}%")
                    ->orWhere('nombres', 'like', "%{$buscar}%")
                    ->orWhere('dni', 'like', "%{$buscar}%");
            })
            ->limit(20)->get(['id', 'codigo_estudiante', 'dni', 'nombres', 'apellidos', 'grado', 'seccion', 'nivel', 'id_anio_escolar']);

        $anioActual = AnioEscolar::actual();
        $estudiantes->map(function ($estudiante) use ($anioActual) {
            $estudiante->tiene_entrega = false;
            if ($anioActual) {
                $estudiante->tiene_entrega = Entrega::where('id_estudiante', $estudiante->id)
                    ->where('id_anio_escolar', $anioActual->id)
                    ->where('estado', 'completada')
                    ->exists();
            }
            return $estudiante;
        });

        return response()->json($estudiantes);
    }

    /**
     * API: Libros disponibles para un grado.
     */
    public function librosPorGrado(Request $request): JsonResponse
    {
        $grado = (int) $request->input('grado', 0);
        $nivel = $request->input('nivel', '');
        
        $libros = Libro::activos()->conStock()
            ->when($grado, fn ($q) => $q->where('grado', $grado))
            ->when($nivel, fn ($q) => $q->where('nivel', $nivel))
            ->orderBy('area')->get(['id', 'codigo_libro', 'nombre', 'area', 'grado', 'cantidad_disponible', 'tipo_material']);
            
        return response()->json($libros);
    }
}
