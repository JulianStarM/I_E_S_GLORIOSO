@extends('layouts.app')

@section('title', 'Entregas')
@section('page-title', 'Historial de Entregas')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                <i data-lucide="clipboard-list" class="h-5 w-5"></i>
            </div>
            Entregas de Libros
        </h2>
        <p class="text-sm text-slate-500 mt-1">Historial de libros entregados a los estudiantes.</p>
    </div>
    
    <a href="{{ route('entregas.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        <i data-lucide="plus" class="h-4 w-4"></i>
        Registrar Entrega
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Código / Fecha</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estudiante</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Recibió</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Libros</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($entregas as $e)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-3">
                            <div class="text-sm font-medium text-slate-700">{{ $e->codigo_general }}</div>
                            <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($e->fecha_entrega)->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="text-sm font-semibold text-slate-800">{{ $e->estudiante->apellidos ?? '' }}</div>
                            <div class="text-xs text-slate-500">{{ $e->estudiante->nombres ?? '' }} | {{ $e->estudiante->grado ?? '' }}° {{ ucfirst($e->estudiante->nivel ?? '') }}</div>
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-600">
                            @if($e->tipo_firmante === 'apoderado')
                                <div class="font-medium text-slate-700 flex items-center gap-1"><i data-lucide="users" class="h-3 w-3"></i> Apoderado</div>
                                <div class="text-xs text-slate-500">{{ $e->padre->nombre_completo ?? 'N/A' }}</div>
                            @else
                                <div class="font-medium text-slate-700 flex items-center gap-1"><i data-lucide="user" class="h-3 w-3"></i> Estudiante</div>
                                <div class="text-xs text-slate-500">Recibe directamente</div>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm text-slate-600 text-center font-medium">
                            {{ $e->total_libros }}
                        </td>
                        <td class="px-6 py-3">
                            @if($e->estado === 'completada')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Completada
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> {{ ucfirst($e->estado) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('entregas.show', $e) }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ver detalles">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                                    <i data-lucide="clipboard-x" class="h-6 w-6 text-slate-400"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-900">No se encontraron entregas</p>
                                <p class="text-xs text-slate-500 mt-1">Registra la entrega de libros a los estudiantes.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($entregas->hasPages())
    <div class="border-t border-slate-100 px-6 py-4 bg-slate-50/50">
        {{ $entregas->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection
