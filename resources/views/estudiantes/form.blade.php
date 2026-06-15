@extends('layouts.app')

@section('title', $estudiante->exists ? 'Editar estudiante' : 'Nuevo estudiante')
@section('page-title', $estudiante->exists ? 'Editar estudiante' : 'Registrar estudiante')

@push('styles')<link rel="stylesheet" href="{{ asset('css/estudiantes.css') }}">@endpush

@section('content')
<div class="page-header">
    <h1><i class="bi bi-person-vcard"></i> {{ $estudiante->exists ? 'Editar estudiante' : 'Nuevo estudiante' }}</h1>
    <a href="{{ route('estudiantes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card-sbl">
    <div class="card-body">
        <form method="POST" action="{{ $estudiante->exists ? route('estudiantes.update', $estudiante) : route('estudiantes.store') }}" class="row g-3" novalidate>
            @csrf
            @if($estudiante->exists) @method('PUT') @endif

            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger mb-0">
                        <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                </div>
            @endif

            <div class="col-md-4">
                <label class="form-label">Codigo</label>
                <input type="text" name="codigo_estudiante" class="form-control" value="{{ old('codigo_estudiante', $estudiante->codigo_estudiante) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" id="dni" class="form-control" value="{{ old('dni', $estudiante->dni) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', optional($estudiante->fecha_nacimiento)->format('Y-m-d')) }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombres" id="nombres" class="form-control" value="{{ old('nombres', $estudiante->nombres) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellidos *</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" value="{{ old('apellidos', $estudiante->apellidos) }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Sexo</label>
                <select name="sexo" class="form-select">
                    <option value="">--</option>
                    <option value="M" @selected(old('sexo', $estudiante->sexo) === 'M')>Masculino</option>
                    <option value="F" @selected(old('sexo', $estudiante->sexo) === 'F')>Femenino</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nivel *</label>
                <select name="nivel" class="form-select" required>
                    @foreach(['inicial','primaria','secundaria'] as $n)
                        <option value="{{ $n }}" @selected(old('nivel', $estudiante->nivel) === $n)>{{ ucfirst($n) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Grado *</label>
                <input type="text" name="grado" class="form-control" value="{{ old('grado', $estudiante->grado) }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Seccion *</label>
                <input type="text" name="seccion" class="form-control" value="{{ old('seccion', $estudiante->seccion) }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Turno</label>
                <select name="turno" class="form-select">
                    <option value="">--</option>
                    @foreach(['mañana','tarde','noche'] as $t)
                        <option value="{{ $t }}" @selected(old('turno', $estudiante->turno) === $t)>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Direccion</label>
                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $estudiante->direccion) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Telefono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $estudiante->telefono) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $estudiante->email) }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Año escolar (ID)</label>
                <input type="number" name="id_anio_escolar" class="form-control" value="{{ old('id_anio_escolar', $estudiante->id_anio_escolar) }}">
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1" @checked(old('estado', $estudiante->estado ?? true))>
                    <label for="estado" class="form-check-label">Estudiante activo</label>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('estudiantes.index') }}" class="btn btn-light">Cancelar</a>
                <button class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>

@endsection
