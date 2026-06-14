@extends('layouts.app')

@section('title', 'Detalle usuario')
@section('page-title', 'Detalle de usuario')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-person-badge"></i> {{ $usuario->nombre_completo }}</h1>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
</div>

<div class="card-sbl">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">DNI</dt>           <dd class="col-sm-9">{{ $usuario->dni ?? '-' }}</dd>
            <dt class="col-sm-3">Email</dt>         <dd class="col-sm-9">{{ $usuario->email }}</dd>
            <dt class="col-sm-3">Telefono</dt>      <dd class="col-sm-9">{{ $usuario->telefono ?? '-' }}</dd>
            <dt class="col-sm-3">Rol</dt>           <dd class="col-sm-9">{{ $usuario->rol }}</dd>
            <dt class="col-sm-3">Estado</dt>        <dd class="col-sm-9">{{ $usuario->estado ? 'Activo' : 'Inactivo' }}</dd>
            <dt class="col-sm-3">Registrado</dt>    <dd class="col-sm-9">{{ optional($usuario->created_at)->format('d/m/Y H:i') }}</dd>
        </dl>
    </div>
</div>
@endsection
