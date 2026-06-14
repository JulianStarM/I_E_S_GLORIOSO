@extends('layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Gestion de usuarios')

@push('styles')<link rel="stylesheet" href="{{ asset('css/usuarios.css') }}">@endpush

@section('content')
<div class="page-header">
    <h1><i class="bi bi-people"></i> Usuarios del sistema</h1>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo usuario
    </a>
</div>

<div class="card-sbl mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar por nombres, apellidos, email o DNI...">
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-outline-primary"><i class="bi bi-search"></i> Buscar</button>
            </div>
            <div class="col-md-3 d-grid">
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card-sbl">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombres y apellidos</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td><strong>{{ $u->apellidos }}</strong>, {{ $u->nombres }}</td>
                            <td>{{ $u->dni ?? '-' }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->telefono ?? '-' }}</td>
                            <td><span class="badge badge-rol">{{ $u->rol }}</span></td>
                            <td>
                                @if($u->estado)
                                    <span class="badge badge-estado-on">Activo</span>
                                @else
                                    <span class="badge badge-estado-off">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('usuarios.destroy', $u) }}" method="POST" class="d-inline form-delete">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No hay usuarios registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $usuarios->links() }}</div>
@endsection

@push('scripts')<script src="{{ asset('js/usuarios.js') }}"></script>@endpush
