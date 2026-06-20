@extends('layouts.app')

@section('title', 'Devolución ' . $devolucion->codigo_general)
@section('page-title', 'Detalle de Devolución')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600">
                    <i data-lucide="rotate-ccw" class="h-5 w-5"></i>
                </div>
                Devolución: {{ $devolucion->codigo_general }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Registrada el {{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y') }}
                a las {{ $devolucion->hora_devolucion }}
            </p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('devoluciones.constancia', $devolucion) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-sm transition-colors">
                <i data-lucide="file-text" class="h-4 w-4"></i>
                Generar Constancia de Devolución
            </a>
            <a href="{{ route('entregas.show', $devolucion->entrega) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg shadow-sm transition-colors">
                <i data-lucide="arrow-left" class="h-4 w-4"></i>
                Ver Entrega Original
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Columna izquierda: info --}}
        <div class="md:col-span-1 space-y-6">

            {{-- Datos del estudiante --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
                <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                    Datos del Estudiante
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Nombres y Apellidos</p>
                        <p class="font-medium text-slate-800">
                            {{ $devolucion->estudiante->apellidos ?? '' }}, {{ $devolucion->estudiante->nombres ?? '' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">DNI</p>
                        <p class="font-medium text-slate-800">{{ $devolucion->estudiante->dni ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nivel y Grado</p>
                        <p class="font-medium text-slate-800">
                            {{ $devolucion->estudiante->grado ?? '' }}°
                            {{ ucfirst($devolucion->estudiante->nivel ?? '') }}
                            — Sección {{ strtoupper($devolucion->estudiante->seccion ?? '') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Datos de la devolución --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
                <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                    Datos de la Devolución
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Código</span>
                        <span class="font-semibold text-slate-800">{{ $devolucion->codigo_general }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Fecha</span>
                        <span class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Hora</span>
                        <span class="font-medium text-slate-800">{{ $devolucion->hora_devolucion }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Registrado por</span>
                        <span class="font-medium text-slate-800">{{ $devolucion->usuarioRegistro->nombre ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Entrega origen</span>
                        <a href="{{ route('entregas.show', $devolucion->entrega) }}"
                           class="font-medium text-blue-600 hover:underline">
                            {{ $devolucion->entrega->codigo_general ?? '-' }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Resumen numérico --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
                <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                    Resumen
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Devueltos</span>
                        <span class="font-bold text-emerald-600 text-base">{{ $devolucion->total_devueltos ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Pendientes</span>
                        <span class="font-bold text-rose-600 text-base">{{ $devolucion->total_no_devueltos ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Deficientes</span>
                        <span class="font-bold text-amber-600 text-base">{{ $devolucion->total_deficientes ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Extraviados</span>
                        <span class="font-bold text-red-700 text-base">{{ $devolucion->total_perdidos ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna derecha: tabla de libros --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-0 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Libros Devueltos</h3>
                    <span class="bg-rose-50 text-rose-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $devolucion->detalles->count() }} Libros
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">N°</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Libro</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Estado Final</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($devolucion->detalles as $index => $detalle)
                                @php
                                    $estado = $detalle->estado_libro ?? 'bueno';
                                    [$badgeBg, $badgeText] = match($estado) {
                                        'bueno'       => ['bg-emerald-50 text-emerald-700', 'Bueno'],
                                        'deteriorado' => ['bg-amber-50 text-amber-700', 'Deteriorado'],
                                        'deficiente'  => ['bg-purple-50 text-purple-700', 'Deficiente'],
                                        'perdido'     => ['bg-red-50 text-red-700', 'Extraviado'],
                                        'no_devuelto' => ['bg-rose-50 text-rose-700', 'No Devuelto'],
                                        default       => ['bg-slate-100 text-slate-700', ucfirst($estado)],
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3 text-sm text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3 text-sm font-medium text-slate-700">
                                        {{ $detalle->libro->codigo_libro ?? '-' }}
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="font-semibold text-slate-800 text-sm">
                                            {{ $detalle->libro->nombre ?? 'Libro no encontrado' }}
                                        </div>
                                        <div class="text-xs text-slate-500">{{ $detalle->libro->area ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium {{ $badgeBg }}">
                                            {{ $badgeText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-slate-500 whitespace-normal max-w-[180px]">
                                        {{ $detalle->observaciones ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($devolucion->observaciones)
                    <div class="p-6 bg-amber-50/50 border-t border-slate-100">
                        <p class="text-xs font-bold text-amber-800 uppercase mb-1">Observaciones generales:</p>
                        <p class="text-sm text-amber-900">{{ $devolucion->observaciones }}</p>
                    </div>
                @endif
            </div>

            {{-- Botón de constancia destacado al final --}}
            <div class="mt-4 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-rose-800">¿Necesitas el comprobante oficial?</p>
                    <p class="text-xs text-rose-600 mt-0.5">Descarga la constancia en PDF lista para imprimir y firmar.</p>
                </div>
                <a href="{{ route('devoluciones.constancia', $devolucion) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-sm transition-colors whitespace-nowrap">
                    <i data-lucide="download" class="h-4 w-4"></i>
                    Generar Constancia PDF
                </a>
            </div>
        </div>
    </div>
@endsection
