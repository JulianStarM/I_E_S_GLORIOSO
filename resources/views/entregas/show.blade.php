@extends('layouts.app')

@section('title', 'Detalle de Entrega')
@section('page-title', 'Detalle de Entrega')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <i data-lucide="file-check" class="h-5 w-5"></i>
                </div>
                Entrega: {{ $entrega->codigo_general }}
            </h2>
            <p class="text-sm text-slate-500 mt-1">Registrada el
                {{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y') }} a las {{ $entrega->hora_entrega }}</p>
        </div>

        <div class="flex gap-2">
            <button
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg shadow-sm transition-colors"
                onclick="window.print()">
                <i data-lucide="printer" class="h-4 w-4"></i>
                Imprimir Constancia
            </button>
            <a href="{{ route('entregas.index') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors">
                <i data-lucide="list" class="h-4 w-4"></i>
                Volver a Entregas
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Información del Estudiante -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
                <h3
                    class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                    Datos del Estudiante</h3>

                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Nombres y Apellidos</p>
                        <p class="font-medium text-slate-800">{{ $entrega->estudiante->apellidos ?? '' }},
                            {{ $entrega->estudiante->nombres ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">DNI</p>
                        <p class="font-medium text-slate-800">{{ $entrega->estudiante->dni ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nivel y Grado</p>
                        <p class="font-medium text-slate-800">{{ $entrega->estudiante->grado ?? '' }}°
                            {{ ucfirst($entrega->estudiante->nivel ?? '') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
                <h3
                    class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">
                    Recepción</h3>

                @if($entrega->tipo_firmante === 'apoderado')
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">Entregado al Apoderado</p>
                            <p class="text-xs text-slate-500">Padre, madre o tutor</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-500">Nombres y Apellidos</p>
                            <p class="font-medium text-slate-800">{{ $entrega->padre->nombre_completo ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">DNI</p>
                            <p class="font-medium text-slate-800">{{ $entrega->padre->dni ?? '-' }}</p>
                        </div>
                        @if($entrega->padre->telefono ?? false)
                            <div>
                                <p class="text-xs text-slate-500">Teléfono</p>
                                <p class="font-medium text-slate-800">{{ $entrega->padre->telefono }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i data-lucide="user" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">Entregado al Estudiante</p>
                            <p class="text-xs text-slate-500">Recibió personalmente</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Lista de Libros -->
        <div class="md:col-span-2">
            <div
                class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-0 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">Libros Entregados</h3>
                    <span
                        class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold">{{ $entrega->total_libros }}
                        Libros</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">N°</th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Código
                                </th>
                                <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Libro
                                </th>
                                <th
                                    class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                    Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($entrega->detalles as $index => $detalle)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3 text-sm text-slate-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-3 text-sm font-medium text-slate-700">
                                        {{ $detalle->libro->codigo_libro ?? '-' }}</td>
                                    <td class="px-6 py-3">
                                        <div class="font-semibold text-slate-800 text-sm">
                                            {{ $detalle->libro->nombre ?? 'Libro Eliminado' }}</div>
                                        <div class="text-xs text-slate-500">{{ $detalle->libro->area ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ ucfirst($detalle->entregas ?? 'entregado') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($entrega->observaciones)
                    <div class="p-6 bg-amber-50/50 border-t border-slate-100">
                        <p class="text-xs font-bold text-amber-800 uppercase mb-1">Observaciones:</p>
                        <p class="text-sm text-amber-900">{{ $entrega->observaciones }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection