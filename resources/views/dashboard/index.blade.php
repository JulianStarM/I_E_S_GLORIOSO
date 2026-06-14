@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard institucional')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div>
        <h1>Bienvenido, {{ auth()->user()->nombres ?? 'Administrador' }}</h1>
        <small class="text-muted">Panel de control - Banco de Libros GSC</small>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card kpi-primary">
            <i class="bi bi-people"></i>
            <div>
                <span>Usuarios</span>
                <strong>{{ $totalUsuarios }}</strong>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card kpi-success">
            <i class="bi bi-mortarboard"></i>
            <div>
                <span>Estudiantes</span>
                <strong>{{ $totalEstudiantes }}</strong>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card kpi-warning">
            <i class="bi bi-book"></i>
            <div>
                <span>Libros</span>
                <strong>{{ $totalLibros }}</strong>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
        <div class="kpi-card kpi-danger">
            <i class="bi bi-arrow-left-right"></i>
            <div>
                <span>Entregas</span>
                <strong>{{ $totalEntregas }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="card-sbl">
    <div class="card-body">
        <h6 class="text-muted mb-3"><i class="bi bi-exclamation-triangle"></i> Stock bajo (libros con 5 o menos unidades)</h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Titulo</th>
                        <th class="text-end">Stock disponible</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockBajo as $row)
                        <tr>
                            <td>{{ $row->codigo ?? '-' }}</td>
                            <td>{{ $row->titulo ?? '-' }}</td>
                            <td class="text-end"><span class="badge bg-danger">{{ $row->stock_disponible ?? 0 }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">Sin alertas de stock</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
