@extends('layouts.app')

@section('title', 'Nuevo Libro')
@section('page-title', 'Registrar Nuevo Libro')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('libros.index') }}" class="text-slate-500 hover:text-slate-700 transition-colors">
            <i data-lucide="arrow-left" class="h-5 w-5"></i>
        </a>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Registrar Nuevo Material</h2>
    </div>
    <p class="text-sm text-slate-500 ml-8">Ingresa los datos del nuevo libro o material educativo.</p>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
    <form action="{{ route('libros.store') }}" method="POST" class="space-y-6">
        @csrf
        
        @include('libros.form')
        
        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('libros.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-colors flex items-center gap-2">
                <i data-lucide="save" class="h-4 w-4"></i>
                Guardar Libro
            </button>
        </div>
    </form>
</div>
@endsection
