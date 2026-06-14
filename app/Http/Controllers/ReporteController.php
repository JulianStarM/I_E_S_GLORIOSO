<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function index(): View
    {
        return view('reportes.index');
    }

    public function porGrado(Request $request): View
    {
        $datos = DB::table('v_reporte_por_grado_seccion')
            ->when($request->filled('anio'), fn ($q) => $q->where('anio_escolar', $request->input('anio')))
            ->orderBy('grado')->orderBy('seccion')->get();
        return view('reportes.por_grado', compact('datos'));
    }

    public function stockLibros(Request $request): View
    {
        $datos = DB::table('v_stock_libros')
            ->when($request->filled('grado'), fn ($q) => $q->where('grado', $request->input('grado')))
            ->when($request->filled('nivel'), fn ($q) => $q->where('nivel', $request->input('nivel')))
            ->orderBy('grado')->orderBy('area')->get();
        return view('reportes.stock', compact('datos'));
    }

    public function pendientes(): View
    {
        $datos = DB::table('v_estudiantes_sin_devolucion')->orderBy('grado')->orderBy('seccion')->get();
        return view('reportes.pendientes', compact('datos'));
    }
}
