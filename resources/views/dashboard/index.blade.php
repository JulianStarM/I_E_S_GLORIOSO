@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-800 tracking-tight">@yield('page-title', 'Dashboard institucional')</h2>
    <p class="text-sm text-slate-500 mt-1">Resumen institucional</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Estudiantes Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-[0_8px_20px_-6px_rgba(6,81,237,0.15)] transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Estudiantes</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalEstudiantes ?? 0 }}</h3>
            </div>
            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center">
                <i data-lucide="graduation-cap" class="h-6 w-6 text-blue-600"></i>
            </div>
        </div>
    </div>

    <!-- Títulos Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-[0_8px_20px_-6px_rgba(6,81,237,0.15)] transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Títulos</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalLibros ?? 0 }}</h3>
            </div>
            <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center">
                <i data-lucide="book" class="h-6 w-6 text-emerald-600"></i>
            </div>
        </div>
    </div>

    <!-- Ejemplares disponibles Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-[0_8px_20px_-6px_rgba(6,81,237,0.15)] transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Ejemplares disponibles</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalEjemplares ?? 599 }}</h3>
            </div>
            <div class="h-12 w-12 rounded-full bg-amber-50 flex items-center justify-center">
                <i data-lucide="package" class="h-6 w-6 text-amber-600"></i>
            </div>
        </div>
    </div>

    <!-- Entregas registradas Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:shadow-[0_8px_20px_-6px_rgba(6,81,237,0.15)] transition-shadow duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Entregas registradas</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $totalEntregas ?? 0 }}</h3>
            </div>
            <div class="h-12 w-12 rounded-full bg-purple-50 flex items-center justify-center">
                <i data-lucide="clipboard-list" class="h-6 w-6 text-purple-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stock Alerts Section -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
    <div class="p-6 border-b border-slate-100">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            Alertas de stock
        </h3>
        <p class="text-sm text-slate-500 mt-1">Títulos con stock disponible bajo</p>
    </div>
    
    <div class="w-full overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">Código</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">Título</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100 text-right">Stock disponible</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($stockBajo ?? [] as $row)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $row->codigo ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $row->titulo ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-right">
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10">
                                {{ $row->stock_disponible ?? 0 }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                                    <i data-lucide="check-circle-2" class="h-6 w-6 text-emerald-500"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-900">Sin alertas.</p>
                                <p class="text-xs text-slate-500 mt-1">Agotados: 0</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
