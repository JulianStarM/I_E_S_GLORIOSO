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
            <button type="button" onclick="openModal('modalNuevaEntrega')" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Nueva Entrega
            </button>
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

    {{-- Filtros y tabla --}}
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="p-6 border-b border-slate-100">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Historial de entregas</h2>
                    <p class="mt-1 text-sm text-slate-500">Busca y filtra las entregas registradas.</p>
                </div>
                <span class="inline-flex items-center rounded-2xl bg-slate-100 px-3 py-2 text-sm text-slate-700">{{ $entregas->total() }} registros</span>
            </div>

            <form id="filtroEntregas" method="GET" action="{{ route('entregas.index') }}" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div class="relative lg:col-span-2">
                    <i data-lucide="search" class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="DNI, nombres, apellidos, código..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-11 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100" />
                </div>
                <select name="grado" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Todos los grados</option>
                    @foreach($gradoOptions as $grado)
                        <option value="{{ $grado }}" @selected(request('grado') == $grado)>{{ $grado }}° Grado</option>
                    @endforeach
                </select>
                <select name="seccion" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <option value="">Todas las secciones</option>
                    @foreach($seccionOptions as $seccion)
                        <option value="{{ $seccion }}" @selected(request('seccion') == $seccion)>Sección {{ $seccion }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <i data-lucide="filter" class="h-4 w-4 mr-2"></i> Filtrar
                </button>
            </form>
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
                <tbody class="divide-y divide-slate-100" id="tabla-entregas-body">
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

{{-- =============== MODAL NUEVA ENTREGA =============== --}}
<div id="modalNuevaEntrega" class="fixed inset-0 z-[999] hidden" role="dialog" aria-modal="true">
    <div class="modal-overlay fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300 opacity-0" onclick="closeModal('modalNuevaEntrega')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="modal-panel pointer-events-auto w-full max-w-3xl bg-white rounded-2xl shadow-2xl transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="h-5 w-5 text-blue-600"></i>
                    Registrar Nueva Entrega
                </h3>
                <button type="button" onclick="closeModal('modalNuevaEntrega')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <form id="formNuevaEntrega" class="space-y-6">
                    {{-- Paso 1: Buscar Estudiante --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">1. Seleccionar Estudiante</h4>
                        <div class="relative" id="entrega-buscar-wrapper">
                            <div class="relative">
                                <i data-lucide="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" id="modal_buscar_est" class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Buscar por DNI, nombres o apellidos..." autocomplete="off">
                            </div>
                            <div id="modal_resultados_est" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg hidden max-h-48 overflow-y-auto"></div>
                        </div>
                        <input type="hidden" name="id_estudiante" id="modal_id_estudiante">
                        <div id="modal_est_info" class="mt-3 p-4 bg-blue-50 border border-blue-200 rounded-xl hidden">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs text-slate-500">Estudiante seleccionado:</p>
                                    <p class="font-bold text-slate-800 text-base" id="modal_lbl_nombre"></p>
                                    <div class="flex gap-4 mt-1 text-sm text-slate-600">
                                        <span>DNI: <strong id="modal_lbl_dni"></strong></span>
                                        <span>Grado: <strong id="modal_lbl_grado"></strong></span>
                                        <span>Nivel: <strong id="modal_lbl_nivel"></strong></span>
                                    </div>
                                </div>
                                <button type="button" id="modal_btn_cambiar_est" class="text-xs text-blue-600 hover:text-blue-700 font-medium bg-blue-100 hover:bg-blue-200 px-3 py-1.5 rounded-lg transition-colors">Cambiar</button>
                            </div>
                            <div id="modal_alerta_entrega" class="hidden mt-2 p-2 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg text-xs flex items-start gap-2">
                                <i data-lucide="alert-triangle" class="h-4 w-4 shrink-0 mt-0.5"></i>
                                <span><strong>¡Atención!</strong> Este estudiante ya tiene una entrega registrada.</span>
                            </div>
                        </div>
                    </div>

                    {{-- Paso 2: Libros --}}
                    <div id="modal_sec_libros" class="hidden">
                        <h4 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3 flex items-center justify-between">
                            <span>2. Libros Asignados</span>
                            <span class="text-xs font-normal text-slate-500 normal-case bg-slate-100 px-2 py-1 rounded-md" id="modal_lbl_total_libros">0 libros</span>
                        </h4>
                        <div id="modal_loading_libros" class="hidden p-6 text-center text-slate-500 text-sm"><i data-lucide="loader-2" class="h-5 w-5 animate-spin mx-auto mb-2"></i>Cargando libros...</div>
                        <div id="modal_lista_libros" class="space-y-2 max-h-48 overflow-y-auto"></div>
                    </div>

                    {{-- Paso 3: Recepción --}}
                    <div id="modal_sec_recepcion" class="hidden">
                        <h4 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-3">3. ¿Quién recibe?</h4>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="tipo_firmante" value="estudiante" class="peer sr-only" checked>
                                <div class="p-3 border border-slate-200 rounded-xl hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center gap-2 text-sm">
                                    <i data-lucide="user" class="h-4 w-4 text-blue-600"></i>
                                    <span class="font-medium text-slate-800">Estudiante</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="tipo_firmante" value="apoderado" class="peer sr-only">
                                <div class="p-3 border border-slate-200 rounded-xl hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-center gap-2 text-sm">
                                    <i data-lucide="users" class="h-4 w-4 text-blue-600"></i>
                                    <span class="font-medium text-slate-800">Apoderado</span>
                                </div>
                            </label>
                        </div>
                        <div id="modal_form_apoderado" class="hidden bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-3 mb-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div><label class="block text-xs text-slate-500 mb-1">DNI *</label><input type="text" name="apoderado_dni" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></div>
                                <div><label class="block text-xs text-slate-500 mb-1">Parentesco *</label>
                                    <select name="apoderado_parentesco" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        <option value="">Seleccione...</option>
                                        <option value="Madre">Madre</option><option value="Padre">Padre</option><option value="Tutor">Tutor/a</option><option value="Otro">Otro</option>
                                    </select>
                                </div>
                                <div><label class="block text-xs text-slate-500 mb-1">Nombres *</label><input type="text" name="apoderado_nombres" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></div>
                                <div><label class="block text-xs text-slate-500 mb-1">Apellidos *</label><input type="text" name="apoderado_apellidos" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></div>
                            </div>
                        </div>
                        <div><label class="block text-xs text-slate-500 mb-1">Observaciones</label><textarea name="observaciones" rows="2" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Opcional"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalNuevaEntrega')" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50">Cancelar</button>
                <button type="button" id="modal_btn_guardar_entrega" class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 flex items-center gap-2 opacity-50 cursor-not-allowed btn-submit-modal" disabled>
                    <i data-lucide="check-circle" class="h-4 w-4"></i> Registrar Entrega
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============ ENTREGA MODAL LOGIC ============
    const mBuscar = document.getElementById('modal_buscar_est');
    const mResultados = document.getElementById('modal_resultados_est');
    const mIdEst = document.getElementById('modal_id_estudiante');
    const mEstInfo = document.getElementById('modal_est_info');
    const mBuscarWrapper = document.getElementById('entrega-buscar-wrapper');
    const mSecLibros = document.getElementById('modal_sec_libros');
    const mSecRecepcion = document.getElementById('modal_sec_recepcion');
    const mListaLibros = document.getElementById('modal_lista_libros');
    const mLoadingLibros = document.getElementById('modal_loading_libros');
    const mLblTotal = document.getElementById('modal_lbl_total_libros');
    const mBtnGuardar = document.getElementById('modal_btn_guardar_entrega');
    const mAlerta = document.getElementById('modal_alerta_entrega');
    let timeoutBusq = null;

    if (mBuscar) {
        mBuscar.addEventListener('input', function() {
            const q = this.value.trim();
            clearTimeout(timeoutBusq);
            if (q.length < 2) { mResultados.classList.add('hidden'); return; }
            timeoutBusq = setTimeout(() => {
                fetch(`{{ route('entregas.api.buscar-estudiantes') }}?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(data => {
                        mResultados.innerHTML = '';
                        if (data.length === 0) {
                            mResultados.innerHTML = '<div class="p-3 text-sm text-slate-500">No se encontraron estudiantes.</div>';
                        } else {
                            data.forEach(est => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-0 text-sm';
                                div.innerHTML = `<div class="font-medium text-slate-800">${est.apellidos}, ${est.nombres}</div><div class="text-xs text-slate-500">DNI: ${est.dni} | ${est.grado}° ${est.nivel || ''}</div>`;
                                div.addEventListener('click', () => seleccionarEstModal(est));
                                mResultados.appendChild(div);
                            });
                        }
                        mResultados.classList.remove('hidden');
                    }).catch(() => { mResultados.innerHTML = '<div class="p-3 text-sm text-red-500">Error al buscar.</div>'; mResultados.classList.remove('hidden'); });
            }, 300);
        });
    }

    document.addEventListener('click', (e) => {
        if (mBuscar && !mBuscar.contains(e.target) && !mResultados.contains(e.target)) mResultados.classList.add('hidden');
    });

    function seleccionarEstModal(est) {
        mResultados.classList.add('hidden');
        mBuscarWrapper.classList.add('hidden');
        mIdEst.value = est.id;
        document.getElementById('modal_lbl_nombre').textContent = `${est.apellidos}, ${est.nombres}`;
        document.getElementById('modal_lbl_dni').textContent = est.dni;
        document.getElementById('modal_lbl_grado').textContent = est.grado + '°';
        document.getElementById('modal_lbl_nivel').textContent = (est.nivel || '').charAt(0).toUpperCase() + (est.nivel || '').slice(1);
        if (est.tiene_entrega) {
            mAlerta.classList.remove('hidden');
            mBtnGuardar.disabled = true; mBtnGuardar.classList.add('opacity-50','cursor-not-allowed');
            mSecLibros.classList.add('hidden'); mSecRecepcion.classList.add('hidden');
        } else {
            mAlerta.classList.add('hidden');
            cargarLibrosModal(est.grado, est.nivel);
            mSecRecepcion.classList.remove('hidden');
        }
        mEstInfo.classList.remove('hidden');
        lucide.createIcons();
    }

    document.getElementById('modal_btn_cambiar_est')?.addEventListener('click', () => {
        mEstInfo.classList.add('hidden'); mSecLibros.classList.add('hidden'); mSecRecepcion.classList.add('hidden');
        mBuscarWrapper.classList.remove('hidden'); mBuscar.value = ''; mIdEst.value = '';
        mBtnGuardar.disabled = true; mBtnGuardar.classList.add('opacity-50','cursor-not-allowed');
        mBuscar.focus();
    });

    function cargarLibrosModal(grado, nivel) {
        mSecLibros.classList.remove('hidden'); mListaLibros.innerHTML = ''; mLoadingLibros.classList.remove('hidden');
        fetch(`{{ route('entregas.api.libros-por-grado') }}?grado=${grado}&nivel=${nivel}`)
            .then(r => r.json())
            .then(data => {
                mLoadingLibros.classList.add('hidden');
                if (data.length === 0) {
                    mListaLibros.innerHTML = '<div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600">No hay libros disponibles para este grado.</div>';
                    mBtnGuardar.disabled = true; mBtnGuardar.classList.add('opacity-50','cursor-not-allowed');
                    return;
                }
                let html = '';
                data.forEach(l => {
                    html += `<label class="flex items-center justify-between p-2.5 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer has-[:checked]:bg-blue-50 has-[:checked]:border-blue-200 text-sm">
                        <div class="flex items-center gap-2"><input type="checkbox" name="libros[]" value="${l.id}" checked class="h-4 w-4 rounded border-slate-300 text-blue-600 modal-cb-libro"><div><p class="font-semibold text-slate-800">${l.nombre}</p><p class="text-xs text-slate-500">${l.area}</p></div></div>
                        <span class="bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-md text-xs">Disp: ${l.cantidad_disponible}</span></label>`;
                });
                mListaLibros.innerHTML = html;
                actualizarTotalModal();
                document.querySelectorAll('.modal-cb-libro').forEach(cb => cb.addEventListener('change', actualizarTotalModal));
            });
    }

    function actualizarTotalModal() {
        const count = document.querySelectorAll('.modal-cb-libro:checked').length;
        mLblTotal.textContent = `${count} libros seleccionados`;
        if (count === 0) { mBtnGuardar.disabled = true; mBtnGuardar.classList.add('opacity-50','cursor-not-allowed'); }
        else { mBtnGuardar.disabled = false; mBtnGuardar.classList.remove('opacity-50','cursor-not-allowed'); }
    }

    // Toggle apoderado form
    document.querySelectorAll('#formNuevaEntrega input[name="tipo_firmante"]').forEach(radio => {
        radio.addEventListener('change', (e) => {
            document.getElementById('modal_form_apoderado').classList.toggle('hidden', e.target.value !== 'apoderado');
        });
    });

    // Submit entrega
    mBtnGuardar?.addEventListener('click', () => {
        submitModalForm('formNuevaEntrega', '{{ route("entregas.api.store") }}', 'modalNuevaEntrega', () => {
            setTimeout(() => location.reload(), 800);
        });
    });
});
</script>
@endpush