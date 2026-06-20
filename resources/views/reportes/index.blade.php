@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Reportes')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Reportes</h1>
                <p class="text-sm text-slate-500">Accede a los informes de entregas, devoluciones y stock de libros.</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('reportes.por_grado') }}" class="block rounded-2xl border border-slate-200 bg-slate-50 p-5 text-slate-900 hover:bg-slate-100 transition">
                <h2 class="font-semibold text-lg mb-2">Por grado</h2>
                <p class="text-sm text-slate-600">Informe de entregas y devoluciones por grado y sección.</p>
            </a>
            <a href="{{ route('reportes.stock') }}" class="block rounded-2xl border border-slate-200 bg-slate-50 p-5 text-slate-900 hover:bg-slate-100 transition">
                <h2 class="font-semibold text-lg mb-2">Stock de libros</h2>
                <p class="text-sm text-slate-600">Visualiza el inventario y existencias de cada libro por grado.</p>
            </a>
            <a href="{{ route('reportes.pendientes') }}" class="block rounded-2xl border border-slate-200 bg-slate-50 p-5 text-slate-900 hover:bg-slate-100 transition">
                <h2 class="font-semibold text-lg mb-2">Pendientes</h2>
                <p class="text-sm text-slate-600">Lista de estudiantes con devoluciones pendientes.</p>
            </a>
        </div>
    </div>
</div>
@endsection
