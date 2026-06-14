@extends('layouts.app')

@section('title', 'Estudiantes')
@section('page-title', 'Gestion de estudiantes')

@push('styles')<link rel="stylesheet" href="{{ asset('css/estudiantes.css') }}">@endpush

@section('content')
<div class="page-header">
    <h1><i class="bi bi-mortarboard"></i> Estudiantes</h1>
    <a href="{{ route('estudiantes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo estudiante
    </a>
</div>

<div class="card-sbl mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Buscar nombres, apellidos, DNI, codigo...">
            </div>
            <div class="col-md-2">
                <select name="nivel" class="form-select">
                    <option value="">Nivel</option>
                    @foreach(['Inicial','Primaria','Secundaria'] as $n)
                        <option value="{{ $n }}" @selected($nivel === $n)>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="grado" value="{{ $grado }}" class="form-control" placeholder="Grado">
            </div>
            <div class="col-md-2">
                <input type="text" name="seccion" value="{{ $seccion }}" class="form-control" placeholder="Seccion">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary"><i class="bi bi-funnel"></i> Filtrar</button>
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
                        <th>Codigo</th>
                        <th>DNI</th>
                        <th>Apellidos y nombres</th>
                        <th>Nivel</th>
                        <th>Grado</th>
                        <th>Seccion</th>
                        <th>Turno</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($estudiantes as $e)
                        <tr>
                            <td>{{ $e->codigo ?? '-' }}</td>
                            <td>{{ $e->dni ?? '-' }}</td>
                            <td><strong>{{ $e->apellidos }}</strong>, {{ $e->nombres }}</td>
                            <td>{{ $e->nivel }}</td>
                            <td>{{ $e->grado }}</td>
                            <td>{{ $e->seccion }}</td>
                            <td>{{ $e->turno ?? '-' }}</td>
                            <td>
                                @if($e->estado)
                                    <span class="badge badge-estado-on">Activo</span>
                                @else
                                    <span class="badge badge-estado-off">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('estudiantes.show', $e) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('estudiantes.edit', $e) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('estudiantes.destroy', $e) }}" method="POST" class="d-inline form-delete">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No se encontraron estudiantes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $estudiantes->links() }}</div>
@endsection

@push('scripts')<script src="{{ asset('js/estudiantes.js') }}"></script>@endpush
