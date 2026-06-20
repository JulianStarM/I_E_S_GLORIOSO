<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas publicas (Login)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas protegidas (Administrador / Bibliotecario)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('usuarios', UsuarioController::class);

    Route::get('estudiantes/buscar-dni', [EstudianteController::class, 'buscarDni'])->name('estudiantes.buscar_dni');
    Route::resource('estudiantes', EstudianteController::class);

    Route::resource('libros', LibroController::class);

    // Entregas
    Route::get('entregas/api/buscar-estudiantes', [EntregaController::class, 'buscarEstudiantes'])->name('entregas.api.buscar-estudiantes');
    Route::get('entregas/api/libros-por-grado', [EntregaController::class, 'librosPorGrado'])->name('entregas.api.libros-por-grado');
    Route::resource('entregas', EntregaController::class);
    Route::get('entregas/{entrega}/constancia', [EntregaController::class, 'constancia'])->name('entregas.constancia');

    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('reportes/por-grado', [ReporteController::class, 'porGrado'])->name('reportes.por_grado');
    Route::get('reportes/stock', [ReporteController::class, 'stockLibros'])->name('reportes.stock');
    Route::get('reportes/pendientes', [ReporteController::class, 'pendientes'])->name('reportes.pendientes');

    Route::get('devoluciones/{devolucion}', [DevolucionController::class, 'show'])->name('devoluciones.show');
    Route::get('devoluciones/{devolucion}/constancia', [DevolucionController::class, 'constancia'])->name('devoluciones.constancia');

});
