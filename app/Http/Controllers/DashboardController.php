<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Estudiante;
use App\Models\Libro;
use App\Models\Usuario;

class DashboardController extends Controller
{
    public function index()
    {
        $libros = Libro::select([
            'id',
            'codigo_libro as codigo',
            'nombre as titulo',
            'area',
            'grado',
            'cantidad_total',
            'cantidad_disponible',
        ])->activos()->get();

        $estudiantesCount = Estudiante::activos()->count();

        $entregas = Entrega::with('detalles')->get();

        // cálculos derivados
        $totalStock = $libros->sum('cantidad_disponible');
        $stockTotal = $libros->sum('cantidad_total');
        $stockBajo = $libros->filter(function ($l) {
            $total = $l->cantidad_total ?? 0;
            $disp = $l->cantidad_disponible ?? 0;

            return $disp <= max(10, ($total * 0.1));
        })->values();

        $agotados = $libros->where('cantidad_disponible', 0)->count();

        $librosEntregados = $entregas->sum(function ($e) {
            return collect($e->detalles)->sum('cantidad');
        });

        $estudiantesConEntrega = $entregas->pluck('id_estudiante')->unique()->count();
        $estudiantesPendientes = max(0, $estudiantesCount - $estudiantesConEntrega);
        $avance = $estudiantesCount === 0 ? 0 : (int) round(($estudiantesConEntrega / $estudiantesCount) * 100);

        return view('dashboard.index', [
            'totalUsuarios' => Usuario::count(),
            'totalEstudiantes' => $estudiantesCount,
            'totalLibros' => $libros->count(),
            'totalEntregas' => $entregas->count(),

            'libros' => $libros,
            'entregas' => $entregas,
            'totalEjemplares' => $totalStock,
            'stockTotal' => $stockTotal,
            'stockBajo' => $stockBajo,
            'agotados' => $agotados,
            'librosEntregados' => $librosEntregados,
            'estudiantesPendientes' => $estudiantesPendientes,
            'estudiantesConEntrega' => $estudiantesConEntrega,
            'avance' => $avance,
        ]);
    }
}
