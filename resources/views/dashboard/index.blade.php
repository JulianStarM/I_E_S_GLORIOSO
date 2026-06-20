@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="space-y-2">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Sistema de Banco de Libros</p>
            <h1 class="text-3xl font-bold text-slate-900">Sistema de Banco de Libros</h1>
        </div>
        <button id="toggleSidebar" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">Toggle Sidebar</button>
    </div>

    <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Resumen institucional</p>
                <p class="mt-2 text-base text-slate-700">Banco de Libros — I.E. Glorioso San Carlos de Puno.</p>
            </div>
        </div>
    </div>
</div>

@php
    $stats = [
        ['label' => 'Estudiantes', 'value' => $totalEstudiantes ?? 0, 'href' => route('estudiantes.index'), 'icon' => 'graduation-cap', 'color' => 'bg-sky-50 text-sky-600'],
        ['label' => 'Títulos', 'value' => $totalLibros ?? 0, 'href' => route('libros.index'), 'icon' => 'book-open', 'color' => 'bg-emerald-50 text-emerald-600'],
        ['label' => 'Stock disponible', 'value' => $totalEjemplares ?? 0, 'href' => route('libros.index'), 'icon' => 'package', 'color' => 'bg-amber-50 text-amber-600'],
        ['label' => 'Entregas', 'value' => $totalEntregas ?? 0, 'href' => route('entregas.index'), 'icon' => 'clipboard-list', 'color' => 'bg-violet-50 text-violet-600'],
        ['label' => 'Libros entregados', 'value' => $librosEntregados ?? 0, 'href' => route('entregas.index'), 'icon' => 'check-circle', 'color' => 'bg-emerald-50 text-emerald-600'],
        ['label' => 'Estudiantes pendientes', 'value' => $estudiantesPendientes ?? 0, 'href' => route('entregas.index'), 'icon' => 'alert-triangle', 'color' => 'bg-rose-50 text-rose-600'],
    ];
@endphp

<div class="grid gap-4 xl:grid-cols-[1.6fr_1fr]">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($stats as $stat)
            <a href="{{ $stat['href'] }}" class="group block overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $stat['value'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl {{ $stat['color'] }}">
                        <i data-lucide="{{ $stat['icon'] }}" class="h-5 w-5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs font-semibold text-slate-500 transition group-hover:text-slate-900">
                    Ver detalle
                    <i data-lucide="arrow-right" class="h-3 w-3"></i>
                </div>
            </a>
        @endforeach
    </div>

    <div class="space-y-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Avance de entrega</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $estudiantesConEntrega ?? 0 }} de {{ $totalEstudiantes ?? 0 }} estudiantes han recibido sus libros.</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">{{ $avance ?? 0 }}%</span>
            </div>
            <div class="mt-5 h-3 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-500" style="width: {{ $avance ?? 0 }}%"></div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Alertas de stock</p>
                    <p class="mt-2 text-sm text-slate-500">Títulos con stock disponible bajo. Total: {{ $stockTotal ?? 0 }}</p>
                </div>
                <span class="rounded-full bg-rose-50 px-3 py-1 text-sm font-semibold text-rose-700">{{ $stockBajo->count() ?? 0 }}</span>
            </div>
            @if(($stockBajo->count() ?? 0) === 0)
                <div class="mt-5 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-4 text-center text-sm text-slate-500">Sin alertas.</div>
            @else
                <div class="mt-5 grid gap-3">
                    @foreach($stockBajo->take(5) as $libro)
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-3 text-sm text-slate-700">
                            <div>
                                <p class="font-medium">{{ $libro->titulo ?? $libro->codigo }}</p>
                                <p class="text-xs text-slate-500">{{ $libro->area ?? 'Sin área' }} · Grado {{ $libro->grado ?? '-' }}</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700">{{ $libro->cantidad_disponible ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

