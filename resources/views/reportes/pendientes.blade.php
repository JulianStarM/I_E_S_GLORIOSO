@extends('layouts.app')

@section('title', 'Pendientes de Devolución')
@section('page-title', 'Pendientes de Devolución')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="bg-white shadow-sm rounded-2xl border border-slate-100 p-6">
        <h1 class="text-2xl font-semibold text-slate-900 mb-4">Estudiantes pendientes de devolución</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Grado</th>
                        <th class="px-4 py-3 text-left">Sección</th>
                        <th class="px-4 py-3 text-left">DNI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($datos as $dato)
                        <tr>
                            <td class="px-4 py-3">{{ $dato->nombre_completo ?? ($dato->apellidos . ', ' . $dato->nombres) }}</td>
                            <td class="px-4 py-3">{{ $dato->grado }}</td>
                            <td class="px-4 py-3">{{ $dato->seccion }}</td>
                            <td class="px-4 py-3">{{ $dato->dni ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">No hay estudiantes pendientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
