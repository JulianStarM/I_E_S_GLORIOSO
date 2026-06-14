<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\Libro;
use App\Models\Entrega;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalUsuarios' => Usuario::count(),
            'totalEstudiantes' => Estudiante::count(),
            'totalLibros' => Libro::count(),
            'totalEntregas' => Entrega::count(),

            'stockBajo' => Libro::where('cantidad_disponible', '<=', 5)->get(),
        ]);
    }
}