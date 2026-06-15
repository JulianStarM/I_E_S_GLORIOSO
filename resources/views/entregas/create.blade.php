@extends('layouts.app')

@section('title', 'Registrar Entrega')
@section('page-title', 'Registrar Entrega de Libros')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                <i data-lucide="clipboard-list" class="h-5 w-5"></i>
            </div>
            Registrar Entrega
        </h2>
        <p class="text-sm text-slate-500 mt-1">Registra la entrega de libros a un estudiante.</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] p-6">
    <form action="{{ route('entregas.store') }}" method="POST" id="form-entrega" class="space-y-8">
        @csrf

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm mb-6">
                <div class="font-bold mb-2">Hay errores en el formulario:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Paso 1: Buscar Estudiante -->
        <div>
            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">1. Seleccionar Estudiante</h3>
            
            <div class="relative">
                <label class="block text-sm font-medium text-slate-700 mb-1">Buscar por DNI o Nombres</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                    </div>
                    <input type="text" id="buscar_estudiante" class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" placeholder="Escribe para buscar..." autocomplete="off">
                </div>
                <!-- Resultados de búsqueda -->
                <div id="resultados_busqueda" class="absolute z-10 w-full mt-1 bg-white border border-slate-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                    <!-- Js lo llena -->
                </div>
            </div>

            <!-- Datos del estudiante seleccionado -->
            <div id="estudiante_info" class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl hidden relative">
                <input type="hidden" name="id_estudiante" id="id_estudiante" value="{{ old('id_estudiante') }}">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Estudiante Seleccionado:</p>
                        <p class="font-bold text-slate-800 text-lg" id="lbl_nombre_estudiante">-</p>
                        <div class="flex gap-4 mt-2 text-sm text-slate-600">
                            <p><span class="font-medium text-slate-700">DNI:</span> <span id="lbl_dni_estudiante">-</span></p>
                            <p><span class="font-medium text-slate-700">Grado:</span> <span id="lbl_grado_estudiante">-</span></p>
                            <p><span class="font-medium text-slate-700">Nivel:</span> <span id="lbl_nivel_estudiante">-</span></p>
                        </div>
                    </div>
                    <button type="button" id="btn_cambiar_estudiante" class="text-sm text-blue-600 hover:text-blue-700 font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">Cambiar</button>
                </div>
                
                <div id="alerta_entrega" class="hidden mt-3 p-3 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg text-sm flex items-start gap-2">
                    <i data-lucide="alert-triangle" class="h-5 w-5 shrink-0"></i>
                    <div>
                        <strong>¡Atención!</strong> Este estudiante ya tiene una entrega registrada.
                    </div>
                </div>
            </div>
        </div>

        <!-- Paso 2: Libros a Entregar -->
        <div id="seccion_libros" class="hidden">
            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4 flex items-center justify-between">
                <span>2. Libros Asignados</span>
                <span class="text-xs font-normal text-slate-500 normal-case bg-slate-100 px-2 py-1 rounded-md" id="lbl_total_libros">0 libros</span>
            </h3>
            
            <div id="loading_libros" class="hidden p-8 text-center text-slate-500">
                <i data-lucide="loader-2" class="h-6 w-6 animate-spin mx-auto mb-2"></i>
                Cargando libros del grado...
            </div>

            <div id="lista_libros" class="space-y-2">
                <!-- Js lo llena -->
                <p class="text-sm text-slate-500 italic py-4">Selecciona un estudiante para cargar sus libros.</p>
            </div>
        </div>

        <!-- Paso 3: Datos de Recepción -->
        <div id="seccion_recepcion" class="hidden">
            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 mb-4">3. ¿Quién recibe?</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_firmante" value="estudiante" class="peer sr-only" checked>
                    <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 transition-all flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-white shadow-sm flex items-center justify-center text-blue-600 peer-checked:bg-blue-600 peer-checked:text-white">
                            <i data-lucide="user" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">Estudiante</p>
                            <p class="text-xs text-slate-500">Recibe personalmente</p>
                        </div>
                    </div>
                </label>
                
                <label class="cursor-pointer">
                    <input type="radio" name="tipo_firmante" value="apoderado" class="peer sr-only">
                    <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 transition-all flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-white shadow-sm flex items-center justify-center text-blue-600 peer-checked:bg-blue-600 peer-checked:text-white">
                            <i data-lucide="users" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">Apoderado</p>
                            <p class="text-xs text-slate-500">Padre, madre o tutor</p>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Formulario Apoderado (Oculto por defecto) -->
            <div id="form_apoderado" class="hidden bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-4 mb-4">
                <p class="text-sm font-medium text-slate-700 mb-2">Datos del Apoderado</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">DNI *</label>
                        <input type="text" name="apoderado_dni" id="apoderado_dni" class="block w-full rounded-md border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Parentesco *</label>
                        <select name="apoderado_parentesco" id="apoderado_parentesco" class="block w-full rounded-md border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border px-3 py-2">
                            <option value="">Seleccione...</option>
                            <option value="Madre">Madre</option>
                            <option value="Padre">Padre</option>
                            <option value="Tutor">Tutor/a</option>
                            <option value="Hermano/a">Hermano/a</option>
                            <option value="Abuelo/a">Abuelo/a</option>
                            <option value="Tio/a">Tío/a</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Nombres *</label>
                        <input type="text" name="apoderado_nombres" id="apoderado_nombres" class="block w-full rounded-md border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs text-slate-500 mb-1">Apellidos *</label>
                        <input type="text" name="apoderado_apellidos" id="apoderado_apellidos" class="block w-full rounded-md border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border px-3 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs text-slate-500 mb-1">Teléfono</label>
                        <input type="text" name="apoderado_telefono" id="apoderado_telefono" class="block w-full rounded-md border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border px-3 py-2">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                <textarea name="observaciones" rows="2" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" placeholder="Opcional. Ej. Falta firmar un libro."></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('entregas.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                Cancelar
            </a>
            <button type="button" id="btn_guardar" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-sm transition-colors flex items-center gap-2 opacity-50 cursor-not-allowed" disabled>
                <i data-lucide="check-circle" class="h-4 w-4"></i>
                Registrar Entrega
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputBuscar = document.getElementById('buscar_estudiante');
        const resultados = document.getElementById('resultados_busqueda');
        const estInfo = document.getElementById('estudiante_info');
        const inputIdEst = document.getElementById('id_estudiante');
        const btnCambiar = document.getElementById('btn_cambiar_estudiante');
        
        const secLibros = document.getElementById('seccion_libros');
        const loadingLibros = document.getElementById('loading_libros');
        const listaLibros = document.getElementById('lista_libros');
        const lblTotalLibros = document.getElementById('lbl_total_libros');
        
        const secRecepcion = document.getElementById('seccion_recepcion');
        const radiosFirmante = document.querySelectorAll('input[name="tipo_firmante"]');
        const formApoderado = document.getElementById('form_apoderado');
        const btnGuardar = document.getElementById('btn_guardar');
        const form = document.getElementById('form-entrega');
        
        const alertaEntrega = document.getElementById('alerta_entrega');

        let timeoutBusqueda = null;

        radiosFirmante.forEach(radio => {
            radio.addEventListener('change', (e) => {
                if(e.target.value === 'apoderado') {
                    formApoderado.classList.remove('hidden');
                    document.getElementById('apoderado_dni').required = true;
                    document.getElementById('apoderado_nombres').required = true;
                    document.getElementById('apoderado_apellidos').required = true;
                    document.getElementById('apoderado_parentesco').required = true;
                } else {
                    formApoderado.classList.add('hidden');
                    document.getElementById('apoderado_dni').required = false;
                    document.getElementById('apoderado_nombres').required = false;
                    document.getElementById('apoderado_apellidos').required = false;
                    document.getElementById('apoderado_parentesco').required = false;
                }
            });
        });

        inputBuscar.addEventListener('input', function() {
            const q = this.value;
            clearTimeout(timeoutBusqueda);
            
            if(q.length < 2) {
                resultados.classList.add('hidden');
                return;
            }

            timeoutBusqueda = setTimeout(() => {
                fetch(`{{ route('entregas.api.buscar-estudiantes') }}?q=${q}`)
                    .then(r => r.json())
                    .then(data => {
                        resultados.innerHTML = '';
                        if(data.length === 0) {
                            resultados.innerHTML = '<div class="p-3 text-sm text-slate-500">No se encontraron estudiantes.</div>';
                        } else {
                            data.forEach(est => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-0';
                                div.innerHTML = `
                                    <div class="font-medium text-slate-800">${est.apellidos}, ${est.nombres}</div>
                                    <div class="text-xs text-slate-500">DNI: ${est.dni} | ${est.grado}° ${est.nivel}</div>
                                `;
                                div.addEventListener('click', () => seleccionarEstudiante(est));
                                resultados.appendChild(div);
                            });
                        }
                        resultados.classList.remove('hidden');
                    });
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if(!inputBuscar.contains(e.target) && !resultados.contains(e.target)) {
                resultados.classList.add('hidden');
            }
        });

        function seleccionarEstudiante(est) {
            resultados.classList.add('hidden');
            inputBuscar.parentElement.parentElement.classList.add('hidden');
            
            inputIdEst.value = est.id;
            document.getElementById('lbl_nombre_estudiante').textContent = `${est.apellidos}, ${est.nombres}`;
            document.getElementById('lbl_dni_estudiante').textContent = est.dni;
            document.getElementById('lbl_grado_estudiante').textContent = est.grado + '°';
            document.getElementById('lbl_nivel_estudiante').textContent = est.nivel.charAt(0).toUpperCase() + est.nivel.slice(1);
            
            if(est.tiene_entrega) {
                alertaEntrega.classList.remove('hidden');
                btnGuardar.disabled = true;
                btnGuardar.classList.add('opacity-50', 'cursor-not-allowed');
                secLibros.classList.add('hidden');
                secRecepcion.classList.add('hidden');
            } else {
                alertaEntrega.classList.add('hidden');
                btnGuardar.disabled = false;
                btnGuardar.classList.remove('opacity-50', 'cursor-not-allowed');
                cargarLibros(est.grado, est.nivel);
                secRecepcion.classList.remove('hidden');
            }
            
            estInfo.classList.remove('hidden');
        }

        btnCambiar.addEventListener('click', () => {
            estInfo.classList.add('hidden');
            secLibros.classList.add('hidden');
            secRecepcion.classList.add('hidden');
            inputBuscar.parentElement.parentElement.classList.remove('hidden');
            inputBuscar.value = '';
            inputIdEst.value = '';
            inputBuscar.focus();
            btnGuardar.disabled = true;
            btnGuardar.classList.add('opacity-50', 'cursor-not-allowed');
        });

        function cargarLibros(grado, nivel) {
            secLibros.classList.remove('hidden');
            listaLibros.innerHTML = '';
            loadingLibros.classList.remove('hidden');
            
            fetch(`{{ route('entregas.api.libros-por-grado') }}?grado=${grado}&nivel=${nivel}`)
                .then(r => r.json())
                .then(data => {
                    loadingLibros.classList.add('hidden');
                    
                    if(data.length === 0) {
                        listaLibros.innerHTML = '<div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600">No hay libros disponibles en inventario para este grado.</div>';
                        btnGuardar.disabled = true;
                        btnGuardar.classList.add('opacity-50', 'cursor-not-allowed');
                        return;
                    }
                    
                    let html = '';
                    data.forEach(l => {
                        html += `
                        <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group has-[:checked]:bg-blue-50 has-[:checked]:border-blue-200">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="libros[]" value="${l.id}" checked class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-600 transition duration-150 ease-in-out cb-libro">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">${l.nombre}</p>
                                    <p class="text-xs text-slate-500">${l.codigo_libro} | Área: ${l.area}</p>
                                </div>
                            </div>
                            <div class="text-xs font-medium text-slate-500 text-right">
                                <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded-md">Disp: ${l.cantidad_disponible}</span>
                            </div>
                        </label>`;
                    });
                    
                    listaLibros.innerHTML = html;
                    actualizarTotalLibros();
                    
                    document.querySelectorAll('.cb-libro').forEach(cb => {
                        cb.addEventListener('change', actualizarTotalLibros);
                    });
                });
        }

        function actualizarTotalLibros() {
            const count = document.querySelectorAll('.cb-libro:checked').length;
            lblTotalLibros.textContent = `${count} libros seleccionados`;
            if(count === 0) {
                btnGuardar.disabled = true;
                btnGuardar.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                btnGuardar.disabled = false;
                btnGuardar.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        btnGuardar.addEventListener('click', () => {
            const tipo = document.querySelector('input[name="tipo_firmante"]:checked').value;
            if(tipo === 'apoderado') {
                const dni = document.getElementById('apoderado_dni').value;
                const nom = document.getElementById('apoderado_nombres').value;
                const ape = document.getElementById('apoderado_apellidos').value;
                if(!dni || !nom || !ape) {
                    alert('Por favor, completa los datos requeridos del apoderado (DNI, Nombres, Apellidos).');
                    return;
                }
            }
            
            if(document.querySelectorAll('.cb-libro:checked').length === 0) {
                alert('Debe seleccionar al menos un libro para entregar.');
                return;
            }
            
            btnGuardar.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Registrando...';
            btnGuardar.disabled = true;
            form.submit();
        });
    });
</script>
@endpush
