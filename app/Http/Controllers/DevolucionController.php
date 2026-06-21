<?php

namespace App\Http\Controllers;

use App\Models\AnioEscolar;
use App\Models\AuditoriaLog;
use App\Models\DetalleDevolucion;
use App\Models\Devolucion;
use App\Models\Entrega;
use App\Models\InventarioMovimiento;
use App\Models\Libro;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DevolucionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Devolucion::with(['estudiante', 'entrega', 'usuarioRegistro']);
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo_general', 'like', "%{$buscar}%")
                    ->orWhereHas('estudiante', fn ($sq) => $sq->where('apellidos', 'like', "%{$buscar}%")
                        ->orWhere('dni', 'like', "%{$buscar}%"));
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $devoluciones = $query->orderByDesc('fecha_devolucion')->paginate(20)->withQueryString();

        return view('devoluciones.index', compact('devoluciones'));
    }

    public function create(Request $request): View
    {
        $entrega = null;
        if ($request->filled('id_entrega')) {
            $entrega = Entrega::with(['estudiante', 'detalles.libro'])->findOrFail($request->input('id_entrega'));
        }
        $entregasPendientes = Entrega::with('estudiante')
            ->completadas()->sinDevolucion()
            ->orderByDesc('fecha_entrega')->get();

        return view('devoluciones.create', compact('entrega', 'entregasPendientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_entrega' => ['required', 'exists:entregas,id'],
            'tipo_firmante' => ['required', 'in:estudiante,apoderado'],
            'detalles' => ['required', 'array', 'min:1'],
            'detalles.*.id_detalle_entrega' => ['required', 'exists:detalle_entregas,id'],
            'detalles.*.id_libro' => ['required', 'exists:libros,id'],
            'detalles.*.estado_libro' => ['required', 'in:bueno,deteriorado,deficiente,no_devuelto,perdido'],
            'detalles.*.observaciones' => ['nullable', 'string'],
            'observaciones' => ['nullable', 'string'],
        ]);

        $entrega = Entrega::findOrFail($request->input('id_entrega'));
        if ($entrega->tieneDevolucion()) {
            return back()->with('error', 'Esta entrega ya tiene devolución registrada.');
        }

        $anioActual = AnioEscolar::actual();
        DB::beginTransaction();
        try {
            $totalDevueltos = 0;
            $totalNoDevueltos = 0;
            $totalDeficientes = 0;
            $totalPerdidos = 0;

            $devolucion = Devolucion::create([
                'codigo_general' => Devolucion::generarCodigo($anioActual?->anio ?? date('Y')),
                'id_entrega' => $entrega->id,
                'id_estudiante' => $entrega->id_estudiante,
                'id_anio_escolar' => $entrega->id_anio_escolar,
                'id_usuario_registro' => auth()->id(),
                'tipo_firmante' => $request->input('tipo_firmante'),
                'fecha_devolucion' => now()->toDateString(),
                'hora_devolucion' => now()->toTimeString(),
                'estado' => 'completada',
                'observaciones' => $request->input('observaciones'),
                'ip_registro' => $request->ip(),
            ]);

            foreach ($request->input('detalles') as $detalle) {
                DetalleDevolucion::create([
                    'id_devolucion' => $devolucion->id,
                    'id_libro' => $detalle['id_libro'],
                    'id_detalle_entrega' => $detalle['id_detalle_entrega'],
                    'estado_libro' => $detalle['estado_libro'],
                    'observaciones' => $detalle['observaciones'] ?? null,
                ]);

                $libro = Libro::findOrFail($detalle['id_libro']);
                $estado = $detalle['estado_libro'];

                if (in_array($estado, ['bueno', 'deteriorado'])) {
                    $libro->increment('cantidad_disponible');
                    $libro->increment('cantidad_devuelta');
                    $libro->decrement('cantidad_entregada');
                    $totalDevueltos++;
                    $tipoMov = 'devolucion';
                } elseif ($estado === 'deficiente') {
                    $libro->increment('cantidad_deficiente');
                    $libro->decrement('cantidad_entregada');
                    $totalDeficientes++;
                    $tipoMov = 'devolucion';
                } elseif ($estado === 'perdido') {
                    $libro->increment('cantidad_perdida');
                    $libro->decrement('cantidad_entregada');
                    $totalPerdidos++;
                    $tipoMov = 'baja';
                } else {
                    $totalNoDevueltos++;
                    $tipoMov = 'devolucion';
                }

                InventarioMovimiento::create([
                    'id_libro' => $libro->id, 'tipo_movimiento' => $tipoMov,
                    'cantidad' => in_array($estado, ['bueno', 'deteriorado']) ? 1 : 0,
                    'cantidad_anterior' => $libro->cantidad_disponible,
                    'cantidad_nueva' => $libro->cantidad_disponible,
                    'referencia_tipo' => 'devolucion', 'referencia_id' => $devolucion->id,
                    'id_usuario' => auth()->id(),
                    'observaciones' => "Estado: {$estado}",
                ]);
            }

            $devolucion->update(compact('totalDevueltos', 'totalNoDevueltos', 'totalDeficientes', 'totalPerdidos'));
            // Corregir: usar nombres de columna reales
            $devolucion->update([
                'total_devueltos' => $totalDevueltos,
                'total_no_devueltos' => $totalNoDevueltos,
                'total_deficientes' => $totalDeficientes,
                'total_perdidos' => $totalPerdidos,
            ]);

            DB::commit();

            AuditoriaLog::registrar([
                'accion' => 'crear', 'modulo' => 'devoluciones', 'registro_id' => $devolucion->id,
                'descripcion' => "Devolución {$devolucion->codigo_general} registrada",
            ]);

            return redirect()->route('devoluciones.show', $devolucion)
                ->with('success', "Devolución {$devolucion->codigo_general} registrada.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error: '.$e->getMessage())->withInput();
        }
    }

    /**
     * API: Get entregas pendientes para dropdown
     */
    public function pendientesApi(Request $request): JsonResponse
    {
        $q = trim((string) $request->get('q', ''));
        $query = Entrega::with(['estudiante', 'detalles.libro'])
            ->completadas()->sinDevolucion();
            
        if ($q) {
            $query->whereHas('estudiante', function($w) use ($q) {
                $w->where('dni', 'like', "%{$q}%")
                  ->orWhere('nombres', 'like', "%{$q}%")
                  ->orWhere('apellidos', 'like', "%{$q}%");
            })->orWhere('codigo_general', 'like', "%{$q}%");
        }
        
        $entregas = $query->orderByDesc('fecha_entrega')->limit(20)->get();
        return response()->json($entregas);
    }

    /**
     * API: Store devolucion via AJAX (modal form)
     */
    public function storeApi(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id_entrega' => ['required', 'exists:entregas,id'],
                'tipo_firmante' => ['required', 'in:estudiante,apoderado'],
                'detalles' => ['required', 'array', 'min:1'],
                'detalles.*.id_detalle_entrega' => ['required', 'exists:detalle_entregas,id'],
                'detalles.*.id_libro' => ['required', 'exists:libros,id'],
                'detalles.*.entregas' => ['required', 'in:bueno,regular,malo,perdido'],
                'observaciones' => ['nullable', 'string'],
            ]);

            DB::beginTransaction();

            $entrega = Entrega::findOrFail($request->input('id_entrega'));

            if ($entrega->id_devolucion) {
                return response()->json(['success' => false, 'message' => 'Esta entrega ya tiene una devolución registrada.'], 422);
            }

            $devolucion = Devolucion::create([
                'codigo_general' => Devolucion::generarCodigo(AnioEscolar::actual()->anio ?? date('Y')),
                'id_entrega' => $entrega->id,
                'id_estudiante' => $entrega->id_estudiante,
                'id_usuario_registro' => auth()->id(),
                'tipo_firmante' => $request->input('tipo_firmante'),
                'fecha_devolucion' => now()->toDateString(),
                'hora_devolucion' => now()->toTimeString(),
                'total_libros_devueltos' => 0,
                'observaciones' => $request->input('observaciones'),
                'ip_registro' => $request->ip(),
            ]);

            $totalDevueltos = 0;
            foreach ($request->input('detalles') as $detalleData) {
                $libro = Libro::findOrFail($detalleData['id_libro']);
                $estadoFisico = $detalleData['entregas'];

                $detalleDev = DetalleDevolucion::create([
                    'id_devolucion' => $devolucion->id,
                    'id_detalle_entrega' => $detalleData['id_detalle_entrega'],
                    'id_libro' => $libro->id,
                    'estado_fisico' => $estadoFisico,
                ]);

                if ($estadoFisico !== 'perdido') {
                    $libro->increment('cantidad_disponible');
                    
                    InventarioMovimiento::create([
                        'id_libro' => $libro->id, 'tipo_movimiento' => 'devolucion',
                        'cantidad' => 1, 'cantidad_anterior' => $libro->cantidad_disponible - 1,
                        'cantidad_nueva' => $libro->cantidad_disponible,
                        'referencia_tipo' => 'devolucion', 'referencia_id' => $devolucion->id,
                        'id_usuario' => auth()->id(),
                    ]);
                }
                
                $libro->decrement('cantidad_entregada');
                $totalDevueltos++;
            }

            $devolucion->update(['total_libros_devueltos' => $totalDevueltos]);
            $entrega->update(['id_devolucion' => $devolucion->id]);
            
            DB::commit();

            AuditoriaLog::registrar([
                'accion' => 'crear', 'modulo' => 'devoluciones', 'registro_id' => $devolucion->id,
                'descripcion' => "Devolución {$devolucion->codigo_general} registrada: {$totalDevueltos} libros",
            ]);

            return response()->json([
                'success' => true,
                'message' => "Devolución {$devolucion->codigo_general} registrada correctamente.",
                'devolucion' => $devolucion,
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(Devolucion $devolucion): View
    {
        $devolucion->load(['estudiante', 'entrega.detalles.libro', 'detalles.libro', 'usuarioRegistro']);

        return view('devoluciones.show', compact('devolucion'));
    }

    public function constancia(Devolucion $devolucion)
    {
        $devolucion->load(['estudiante', 'padre', 'entrega.detalles.libro', 'detalles.libro', 'usuarioRegistro']);

        $logoPath = 'file://'.str_replace('\\', '/', public_path('glorioso.png'));
        $pdf = Pdf::loadView('reportes.constancia-devolucion', [
            'devolucion' => $devolucion,
            'logo' => $logoPath,
            'fechaEmision' => now()->format('d/m/Y H:i'),
            'constanciaCodigo' => $devolucion->codigo_general,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("CONSTANCIA_DEVOLUCION_{$devolucion->codigo_general}.pdf");
    }
}
