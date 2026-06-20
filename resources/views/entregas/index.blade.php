@extends('layouts.app')

@section('title', 'Entregas')
@section('page-title', 'Entrega de libros')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Entrega de libros</h1>
                <p class="mt-2 text-sm text-slate-500">Asignación de libros por grado con código de entrega único por estudiante.</p>
            </div>
            <a href="{{ route('entregas.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Registrar nueva entrega
            </a>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Estudiantes</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $totalEstudiantes ?? 0 }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Libros</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $totalLibros ?? 0 }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Entregas</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $totalEntregas ?? 0 }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Libros entregados</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $librosEntregados ?? 0 }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Pendientes</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $estudiantesPendientes ?? 0 }}</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Avance</p>
                <p class="mt-3 text-3xl font-semibold text-slate-900">{{ $avance ?? 0 }}%</p>
            </div>
        </div>
    </div>

    <div class="grid gap-4 xl:grid-cols-[1.5fr_0.9fr]">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Registrar nueva entrega</h2>
                    <p class="mt-1 text-sm text-slate-500">Busca por DNI, apellidos o nombres para validar la entrega.</p>
                </div>
                <span class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700">{{ $entregas->total() }} entrega(s) registradas</span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="space-y-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Buscar estudiante</span>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="DNI, nombres, apellidos..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" form="filtroEntregas" />
                </label>
                <label class="space-y-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Grado</span>
                    <select name="grado" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" form="filtroEntregas">
                        <option value="">Todos</option>
                        @foreach($gradoOptions as $grado)
                            <option value="{{ $grado }}" @selected(request('grado') == $grado)>{{ $grado }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-2">
                    <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500">Sección</span>
                    <select name="seccion" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" form="filtroEntregas">
                        <option value="">Todas</option>
                        @foreach($seccionOptions as $seccion)
                            <option value="{{ $seccion }}" @selected(request('seccion') == $seccion)>{{ $seccion }}</option>
                        @endforeach
                    </select>
                </label>
                <div class="flex items-end">
                    <button type="submit" form="filtroEntregas" class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Aplicar filtros</button>
                </div>
            </div>

            <form id="filtroEntregas" method="GET" action="{{ route('entregas.index') }}"></form>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Estudiantes activos</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $totalEstudiantes ?? 0 }}</p>
                </div>
                <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Entregas completadas</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900">{{ $totalEntregas ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Entregas por grado</h2>
            <p class="mt-1 text-sm text-slate-500">Resumen rápido de entregas agrupadas por grado.</p>

            <div class="mt-6 grid gap-3">
                @forelse($entregasPorGrado as $grado => $count)
                    <div class="flex items-center justify-between rounded-3xl border border-slate-100 bg-slate-50 px-4 py-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">Grado {{ $grado }}</p>
                            <p class="text-xs text-slate-500">Entregas registradas</p>
                        </div>
                        <span class="inline-flex min-w-[3rem] items-center justify-center rounded-full bg-blue-600 px-3 py-1 text-sm font-semibold text-white">{{ $count }}</span>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-5 text-center text-sm text-slate-500">
                        Sin datos de entregas por grado.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="p-6 border-b border-slate-100 sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Historial de entregas</h2>
                <p class="mt-1 text-sm text-slate-500">Revisa las últimas entregas y su estado.</p>
            </div>
            <div class="mt-4 flex flex-col gap-3 sm:mt-0 sm:flex-row sm:items-center">
                <div class="relative">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar en el historial" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-11 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:w-80" form="filtroEntregas" />
                </div>
                <span class="inline-flex items-center rounded-2xl bg-slate-100 px-3 py-2 text-sm text-slate-700">{{ $entregas->total() }} registros</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-700">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3">DNI</th>
                        <th class="px-4 py-3">Estudiante</th>
                        <th class="px-4 py-3">Grado</th>
                        <th class="px-4 py-3">Sec.</th>
                        <th class="px-4 py-3 text-right">Libros</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Responsable</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($entregas as $entrega)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 font-medium">{{ $entrega->codigo_general }}</td>
                            <td class="px-4 py-4">{{ $entrega->estudiante->dni ?? '—' }}</td>
                            <td class="px-4 py-4">{{ $entrega->estudiante->apellidos ?? '' }}, {{ $entrega->estudiante->nombres ?? '' }}</td>
                            <td class="px-4 py-4">{{ $entrega->estudiante->grado ?? '—' }}°</td>
                            <td class="px-4 py-4">{{ $entrega->estudiante->seccion ?? '—' }}</td>
                            <td class="px-4 py-4 text-right">{{ $entrega->total_libros }}</td>
                            <td class="px-4 py-4">{{ optional($entrega->fecha_entrega)->format('d/m/Y') }}</td>
                            <td class="px-4 py-4">{{ $entrega->usuarioRegistro->nombres ?? '—' }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $entrega->estado === 'completada' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $entrega->estado === 'completada' ? 'Entregado' : ucfirst($entrega->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('entregas.show', $entrega) }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-12 text-center text-sm text-slate-500">Sin entregas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($entregas->hasPages())
            <div class="border-t border-slate-100 bg-slate-50 px-4 py-4">{{ $entregas->links('pagination::tailwind') }}</div>
        @endif
    </div>
</div>
@endsection