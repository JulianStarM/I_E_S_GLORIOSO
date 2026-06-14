<?php

namespace App\Http\Controllers;

use App\Models\InventarioMovimiento;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventarioController extends Controller
{
    public function index(Request $request): View
    {
        $query = InventarioMovimiento::with(['libro', 'usuario']);
        if ($request->filled('tipo')) { $query->where('tipo_movimiento', $request->input('tipo')); }
        if ($request->filled('id_libro')) { $query->where('id_libro', $request->input('id_libro')); }
        if ($request->filled('fecha_desde')) { $query->whereDate('created_at', '>=', $request->input('fecha_desde')); }
        if ($request->filled('fecha_hasta')) { $query->whereDate('created_at', '<=', $request->input('fecha_hasta')); }

        $movimientos = $query->orderByDesc('created_at')->paginate(30)->withQueryString();

        // Resumen de stock
        $resumenStock = Libro::selectRaw('
            SUM(cantidad_total) as total,
            SUM(cantidad_disponible) as disponible,
            SUM(cantidad_entregada) as entregada,
            SUM(cantidad_devuelta) as devuelta,
            SUM(cantidad_perdida) as perdida,
            SUM(cantidad_deficiente) as deficiente
        ')->first();

        $libros = Libro::orderBy('nombre')->get(['id', 'nombre', 'codigo_libro']);

        return view('inventario.index', compact('movimientos', 'resumenStock', 'libros'));
    }
}
