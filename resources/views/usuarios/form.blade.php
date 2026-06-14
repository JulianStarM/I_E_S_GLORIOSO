@extends('layouts.app')

@section('title', $usuario->exists ? 'Editar usuario' : 'Nuevo usuario')
@section('page-title', $usuario->exists ? 'Editar usuario' : 'Registrar usuario')

@push('styles')<link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">@endpush

@section('content')
<div class="page-header">
    <h1><i class="bi bi-person-gear"></i> {{ $usuario->exists ? 'Editar usuario' : 'Nuevo usuario' }}</h1>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card-sbl">
    <div class="card-body">
        <form method="POST" action="{{ $usuario->exists ? route('usuarios.update', $usuario) : route('usuarios.store') }}" class="row g-3" novalidate>
            @csrf
            @if($usuario->exists) @method('PUT') @endif

            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger mb-0">
                        <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                </div>
            @endif

            <div class="col-md-6">
                <label class="form-label">Nombres *</label>
                <input type="text" name="nombres" class="form-control" value="{{ old('nombres', $usuario->nombres) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Apellidos *</label>
                <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos', $usuario->apellidos) }}" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" value="{{ old('dni', $usuario->dni) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Telefono</label>
                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Rol *</label>
                <select name="rol" class="form-select" required>
                    @foreach(['Administrador','Bibliotecario'] as $r)
                        <option value="{{ $r }}" @selected(old('rol', $usuario->rol) === $r)>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Correo electronico *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contraseña {{ $usuario->exists ? '(dejar vacio para no cambiar)' : '*' }}</label>
                <input type="password" name="password" class="form-control" {{ $usuario->exists ? '' : 'required' }}>
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1" @checked(old('estado', $usuario->estado ?? true))>
                    <label for="estado" class="form-check-label">Usuario activo</label>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('usuarios.index') }}" class="btn btn-light">Cancelar</a>
                <button class="btn btn-primary"><i class="bi bi-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
