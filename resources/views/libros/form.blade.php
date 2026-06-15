@if ($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm">
        <div class="font-bold mb-2 flex items-center gap-2">
            <i data-lucide="alert-circle" class="h-4 w-4"></i>
            Hay errores en el formulario:
        </div>
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Información Principal -->
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Información Principal</h3>
        
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Código del Libro *</label>
            <input type="text" name="codigo_libro" value="{{ old('codigo_libro', $libro->codigo_libro ?? '') }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nombre / Título *</label>
            <input type="text" name="nombre" value="{{ old('nombre', $libro->nombre ?? '') }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tipo de Material *</label>
                <select name="tipo_material" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
                    <option value="">Seleccione...</option>
                    <option value="libro" @selected(old('tipo_material', $libro->tipo_material ?? '') == 'libro')>Libro</option>
                    <option value="cuaderno_trabajo" @selected(old('tipo_material', $libro->tipo_material ?? '') == 'cuaderno_trabajo')>Cuaderno de Trabajo</option>
                    <option value="guia_docente" @selected(old('tipo_material', $libro->tipo_material ?? '') == 'guia_docente')>Guía Docente</option>
                    <option value="material_complementario" @selected(old('tipo_material', $libro->tipo_material ?? '') == 'material_complementario')>Material Complementario</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Área *</label>
                <input type="text" name="area" value="{{ old('area', $libro->area ?? '') }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nivel *</label>
                <select name="nivel" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
                    <option value="">Seleccione...</option>
                    <option value="inicial" @selected(old('nivel', $libro->nivel ?? '') == 'inicial')>Inicial</option>
                    <option value="primaria" @selected(old('nivel', $libro->nivel ?? 'primaria') == 'primaria')>Primaria</option>
                    <option value="secundaria" @selected(old('nivel', $libro->nivel ?? '') == 'secundaria')>Secundaria</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Grado *</label>
                <select name="grado" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
                    <option value="">Seleccione...</option>
                    @for($i=1; $i<=6; $i++)
                        <option value="{{ $i }}" @selected(old('grado', $libro->grado ?? '') == $i)>{{ $i }}°</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <!-- Detalles Adicionales -->
    <div class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">Detalles Adicionales</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Editorial</label>
                <input type="text" name="editorial" value="{{ old('editorial', $libro->editorial ?? '') }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Año Edición</label>
                <input type="number" name="anio_edicion" value="{{ old('anio_edicion', $libro->anio_edicion ?? '') }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" min="1900" max="{{ date('Y')+1 }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Año Escolar (ID)</label>
            <select name="id_anio_escolar" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">
                <option value="">Ninguno</option>
                @foreach($aniosEscolares ?? [] as $anio)
                    <option value="{{ $anio->id }}" @selected(old('id_anio_escolar', $libro->id_anio_escolar ?? '') == $anio->id)>{{ $anio->anio }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Cantidad Total *</label>
                <input type="number" name="cantidad_total" value="{{ old('cantidad_total', $libro->cantidad_total ?? 0) }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" min="0" required>
                @if(isset($libro))
                <p class="text-xs text-slate-500 mt-1">Disp: {{ $libro->cantidad_disponible }} | Entr: {{ $libro->cantidad_entregada }}</p>
                @endif
            </div>

            @if(isset($libro))
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Estado *</label>
                <select name="estado" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
                    <option value="activo" @selected(old('estado', $libro->estado ?? '') == 'activo')>Activo</option>
                    <option value="agotado" @selected(old('estado', $libro->estado ?? '') == 'agotado')>Agotado</option>
                    <option value="descontinuado" @selected(old('estado', $libro->estado ?? '') == 'descontinuado')>Descontinuado</option>
                    <option value="en_revision" @selected(old('estado', $libro->estado ?? '') == 'en_revision')>En Revisión</option>
                </select>
            </div>
            @endif
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
            <textarea name="observaciones" rows="3" class="block w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">{{ old('observaciones', $libro->observaciones ?? '') }}</textarea>
        </div>
    </div>
</div>
