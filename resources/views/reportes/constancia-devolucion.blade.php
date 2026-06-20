<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Devolución – {{ $constanciaCodigo }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #1a1a2e;
            background: #fff;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 18mm 15mm 18mm;
            position: relative;
        }

        /* ─── ENCABEZADO ─── */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #7a1a1e;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header-logo { display: table-cell; width: 90px; vertical-align: middle; }
        .header-logo img { width: 80px; height: auto; }
        .header-text { display: table-cell; vertical-align: middle; text-align: center; }
        .header-text .inst-name {
            font-size: 10pt;
            font-weight: bold;
            color: #7a1a1e;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            line-height: 1.4;
        }
        .header-text .inst-sub {
            font-size: 9pt;
            color: #444;
            margin-top: 2px;
        }
        .header-right { display: table-cell; width: 90px; vertical-align: middle; text-align: right; }
        .header-right .ugel {
            font-size: 7.5pt;
            color: #555;
            line-height: 1.5;
        }

        /* ─── TÍTULO DOCUMENTO ─── */
        .doc-title-block {
            text-align: center;
            margin: 12px 0 10px;
        }
        .doc-title-block .doc-type {
            font-size: 9pt;
            color: #555;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .doc-title-block .doc-main {
            font-size: 15pt;
            font-weight: bold;
            color: #7a1a1e;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 3px 0;
        }
        .doc-title-block .doc-code {
            display: inline-block;
            background: #7a1a1e;
            color: #fff;
            font-size: 10pt;
            font-weight: bold;
            padding: 3px 18px;
            border-radius: 3px;
            letter-spacing: 0.1em;
            margin-top: 4px;
        }

        /* ─── SECCIÓN ─── */
        .section-title {
            background: #7a1a1e;
            color: #fff;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 4px 10px;
            margin: 10px 0 6px;
        }

        /* ─── DATOS ESTUDIANTE ─── */
        .datos-table { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        .datos-table td { padding: 4px 6px; border: 0.5px solid #e0c0c0; vertical-align: top; }
        .datos-table td.lbl {
            width: 140px;
            background: #fff0f0;
            font-weight: bold;
            color: #7a1a1e;
        }
        .datos-table td.val { color: #222; }

        /* ─── CUERPO ─── */
        .body-text {
            font-size: 9.5pt;
            text-align: justify;
            line-height: 1.7;
            margin: 10px 0 6px;
            color: #222;
        }

        /* ─── TABLA LIBROS ─── */
        .libros-table { width: 100%; border-collapse: collapse; font-size: 8pt; margin-top: 6px; }
        .libros-table thead tr { background: #7a1a1e; color: #fff; }
        .libros-table thead th { padding: 5px 5px; text-align: left; font-weight: bold; }
        .libros-table thead th.center { text-align: center; }
        .libros-table tbody tr:nth-child(even) { background: #fdf5f5; }
        .libros-table tbody td { padding: 4px 5px; border-bottom: 0.5px solid #e8d0d0; color: #222; vertical-align: middle; }
        .libros-table tbody td.center { text-align: center; font-size: 11pt; }
        .libros-table tbody td.estado-bueno    { color: #166534; font-weight: bold; }
        .libros-table tbody td.estado-deteriorado { color: #92400e; font-weight: bold; }
        .libros-table tbody td.estado-perdido  { color: #7a1a1e; font-weight: bold; }
        .libros-table tbody td.estado-deficiente { color: #7c3aed; font-weight: bold; }
        .libros-table tbody td.estado-no_devuelto { color: #dc2626; font-weight: bold; }

        /* ─── RESUMEN ─── */
        .resumen-table { width: 100%; border-collapse: collapse; font-size: 9pt; margin-top: 6px; }
        .resumen-table td { padding: 5px 8px; border: 0.5px solid #e0c0c0; }
        .resumen-table td.lbl { background: #fff0f0; font-weight: bold; color: #7a1a1e; width: 60%; }
        .resumen-table td.val { text-align: center; font-weight: bold; color: #1a1a2e; }

        /* ─── NOTA INSTITUCIONAL ─── */
        .nota-box {
            border-left: 4px solid #7a1a1e;
            padding: 6px 10px;
            background: #fff0f0;
            font-size: 8pt;
            color: #333;
            margin-top: 10px;
            text-align: justify;
        }

        /* ─── FIRMAS ─── */
        .firmas-grid {
            display: table;
            width: 100%;
            margin-top: 22px;
        }
        .firma-cell {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 0 8px;
            vertical-align: bottom;
        }
        .firma-space { height: 45px; }
        .firma-sello { height: 55px; border: 1px dashed #999; margin: 0 10px; display: flex; align-items: center; justify-content: center; }
        .firma-sello-text { font-size: 7pt; color: #999; }
        .firma-line { border-top: 1px solid #1a1a2e; margin: 0 10px; }
        .firma-label { font-size: 7.5pt; color: #333; margin-top: 4px; line-height: 1.4; }
        .firma-label strong { display: block; font-size: 7pt; color: #666; }

        /* ─── PIE ─── */
        .footer {
            margin-top: 16px;
            border-top: 1.5px solid #7a1a1e;
            padding-top: 6px;
            display: table;
            width: 100%;
            font-size: 7.5pt;
            color: #555;
        }
        .footer-left { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }
        .footer strong { color: #7a1a1e; }

        @page { size: A4 portrait; margin: 0; }
        @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
    </style>
</head>
<body>
<div class="page">

    {{-- ══════════════ ENCABEZADO ══════════════ --}}
    <div class="header">
        <div class="header-logo">
            <img src="{{ $logo }}" alt="Logo I.E. Glorioso San Carlos">
        </div>
        <div class="header-text">
            <div class="inst-name">
                Institución Educativa Emblemática<br>
                Glorioso Colegio Nacional San Carlos
            </div>
            <div class="inst-sub">
                Banco de Libros Escolares &nbsp;|&nbsp; Puno, Perú
            </div>
        </div>
        <div class="header-right">
            <div class="ugel">
                UGEL Puno<br>
                DRE Puno<br>
                MINEDU
            </div>
        </div>
    </div>

    {{-- ══════════════ TÍTULO ══════════════ --}}
    <div class="doc-title-block">
        <div class="doc-type">Banco de Libros Escolares</div>
        <div class="doc-main">Constancia de Devolución de Textos</div>
        <div class="doc-code">{{ $constanciaCodigo }}</div>
    </div>

    {{-- ══════════════ DATOS DEL ESTUDIANTE ══════════════ --}}
    <div class="section-title">Datos del Estudiante</div>
    <table class="datos-table">
        <tr>
            <td class="lbl">Código de Estudiante</td>
            <td class="val">{{ $devolucion->estudiante->codigo_estudiante ?? '-' }}</td>
            <td class="lbl">DNI</td>
            <td class="val">{{ $devolucion->estudiante->dni ?? '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">Apellidos y Nombres</td>
            <td class="val" colspan="3">
                {{ strtoupper(($devolucion->estudiante->apellidos ?? '') . ', ' . ($devolucion->estudiante->nombres ?? '')) }}
            </td>
        </tr>
        <tr>
            <td class="lbl">Nivel Educativo</td>
            <td class="val">{{ ucfirst($devolucion->estudiante->nivel ?? '-') }}</td>
            <td class="lbl">Grado</td>
            <td class="val">{{ $devolucion->estudiante->grado ?? '-' }}° Grado</td>
        </tr>
        <tr>
            <td class="lbl">Sección</td>
            <td class="val">{{ strtoupper($devolucion->estudiante->seccion ?? '-') }}</td>
            <td class="lbl">Año Escolar</td>
            <td class="val">{{ $devolucion->entrega->anioEscolar->anio ?? date('Y') }}</td>
        </tr>
        <tr>
            <td class="lbl">Apoderado / Representante</td>
            <td class="val" colspan="3">
                @if($devolucion->tipo_firmante === 'apoderado' && $devolucion->padre ?? false)
                    {{ strtoupper($devolucion->padre->nombre_completo ?? ($devolucion->padre->apellidos . ', ' . $devolucion->padre->nombres)) }}
                    (DNI: {{ $devolucion->padre->dni ?? '-' }})
                @else
                    Devuelto personalmente por el estudiante
                @endif
            </td>
        </tr>
        <tr>
            <td class="lbl">Fecha de Devolución</td>
            <td class="val">{{ \Carbon\Carbon::parse($devolucion->fecha_devolucion)->format('d/m/Y') }}</td>
            <td class="lbl">Hora de Devolución</td>
            <td class="val">{{ $devolucion->hora_devolucion ? \Carbon\Carbon::parse($devolucion->hora_devolucion)->format('H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">Código de Entrega Original</td>
            <td class="val" colspan="3">{{ $devolucion->entrega->codigo_general ?? '-' }}</td>
        </tr>
    </table>

    {{-- ══════════════ CUERPO ══════════════ --}}
    <div class="section-title">Constancia</div>
    <p class="body-text">
        Se deja constancia que el/la estudiante antes mencionado(a) ha realizado la devolución de los textos
        escolares asignados por el Banco de Libros de la Institución Educativa Emblemática Glorioso Colegio
        Nacional San Carlos, correspondientes al año académico <strong>{{ $devolucion->entrega->anioEscolar->anio ?? date('Y') }}</strong>.
        Los detalles de devolución se registran a continuación:
    </p>

    {{-- ══════════════ TABLA DE LIBROS ══════════════ --}}
    <div class="section-title">Detalle de Textos Devueltos</div>

    @php
        $totalAsignados   = $devolucion->detalles->count();
        $totalDevueltos   = 0;
        $totalPendientes  = 0;
    @endphp

    <table class="libros-table">
        <thead>
            <tr>
                <th style="width:25px">N°</th>
                <th style="width:65px">Código</th>
                <th>Título del Libro</th>
                <th class="center" style="width:45px">Devuelto</th>
                <th class="center" style="width:55px">No Devuelto</th>
                <th style="width:75px">Estado Final</th>
                <th style="width:80px">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($devolucion->detalles as $index => $detalle)
                @php
                    $estado = $detalle->estado_libro ?? 'bueno';
                    $devuelto   = !in_array($estado, ['no_devuelto', 'perdido']);
                    $noDevuelto = in_array($estado, ['no_devuelto', 'perdido']);
                    if ($devuelto) $totalDevueltos++;
                    else $totalPendientes++;

                    $estadoLabel = match($estado) {
                        'bueno'       => 'Excelente / Bueno',
                        'deteriorado' => 'Bueno / Regular',
                        'deficiente'  => 'Deteriorado',
                        'perdido'     => 'Extraviado',
                        'no_devuelto' => 'Pendiente',
                        default       => ucfirst($estado),
                    };
                    $estadoClass = match($estado) {
                        'bueno'       => 'estado-bueno',
                        'deteriorado' => 'estado-deteriorado',
                        'deficiente'  => 'estado-deficiente',
                        'perdido'     => 'estado-perdido',
                        'no_devuelto' => 'estado-no_devuelto',
                        default       => '',
                    };
                @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $detalle->libro->codigo_libro ?? '-' }}</td>
                    <td>
                        <strong>{{ $detalle->libro->nombre ?? 'Libro no encontrado' }}</strong>
                        @if($detalle->libro->area ?? false)
                            <br><span style="font-size:7.5pt;color:#666;">{{ $detalle->libro->area }}</span>
                        @endif
                    </td>
                    <td class="center">{{ $devuelto ? '✓' : '' }}</td>
                    <td class="center">{{ $noDevuelto ? '✓' : '' }}</td>
                    <td class="{{ $estadoClass }}">{{ $estadoLabel }}</td>
                    <td style="font-size:7.5pt;color:#555;">{{ $detalle->observaciones ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center" style="color:#999;padding:12px;">Sin detalles registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ══════════════ RESUMEN ══════════════ --}}
    <div class="section-title">Resumen de Devolución</div>
    <table class="resumen-table">
        <tr>
            <td class="lbl">Total de libros asignados en la entrega</td>
            <td class="val">{{ $totalAsignados }}</td>
        </tr>
        <tr>
            <td class="lbl">Total de libros devueltos</td>
            <td class="val" style="color:#166534;">{{ $totalDevueltos }}</td>
        </tr>
        <tr>
            <td class="lbl">Total de libros pendientes / extraviados</td>
            <td class="val" style="color:#dc2626;">{{ $totalPendientes }}</td>
        </tr>
    </table>

    {{-- ══════════════ NOTA INSTITUCIONAL ══════════════ --}}
    <div class="nota-box">
        <strong>NOTA INSTITUCIONAL:</strong> Esta constancia es válida únicamente para efectos de matrícula,
        regularización académica y control del Banco de Libros. Cualquier libro no devuelto o extraviado
        deberá ser repuesto por el estudiante o su representante legal, conforme al reglamento de la institución.
    </div>

    {{-- ══════════════ FIRMAS ══════════════ --}}
    <div class="firmas-grid">
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line"></div>
            <div class="firma-label">
                Firma del Estudiante<br>
                <strong>{{ strtoupper($devolucion->estudiante->apellidos ?? '') }}<br>{{ strtoupper($devolucion->estudiante->nombres ?? '') }}</strong>
            </div>
        </div>
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line"></div>
            <div class="firma-label">
                Firma del Padre / Madre / Apoderado
            </div>
        </div>
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line"></div>
            <div class="firma-label">
                Responsable del Banco de Libros<br>
                <strong>{{ strtoupper($devolucion->usuarioRegistro->nombre ?? $devolucion->usuarioRegistro->nombres ?? '') }}</strong>
            </div>
        </div>
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line" style="border-top-style:dashed;border-color:#999;"></div>
            <div class="firma-label">
                Director(a) — Sello Institucional
            </div>
        </div>
    </div>

    {{-- ══════════════ PIE DE PÁGINA ══════════════ --}}
    <div class="footer">
        <div class="footer-left">
            <strong>Emitido:</strong> {{ $fechaEmision }} &nbsp;|&nbsp;
            <strong>Código de validación:</strong> {{ $constanciaCodigo }}
        </div>
        <div class="footer-right">
            I.E.E. Glorioso Colegio Nacional San Carlos &nbsp;|&nbsp; Banco de Libros Escolares
        </div>
    </div>

</div>
</body>
</html>
