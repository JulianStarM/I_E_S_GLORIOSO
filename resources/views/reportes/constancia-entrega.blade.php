<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Entrega – {{ $constanciaCodigo }}</title>
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
            border-bottom: 3px solid #1a3a6e;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .header-logo { display: table-cell; width: 90px; vertical-align: middle; }
        .header-logo img { width: 80px; height: auto; }
        .header-text { display: table-cell; vertical-align: middle; text-align: center; }
        .header-text .inst-name {
            font-size: 10pt;
            font-weight: bold;
            color: #1a3a6e;
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
            color: #1a3a6e;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin: 3px 0;
        }
        .doc-title-block .doc-code {
            display: inline-block;
            background: #1a3a6e;
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
            background: #1a3a6e;
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
        .datos-table td { padding: 4px 6px; border: 0.5px solid #c8d0e0; vertical-align: top; }
        .datos-table td.lbl {
            width: 140px;
            background: #f0f4ff;
            font-weight: bold;
            color: #1a3a6e;
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
        .libros-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-top: 6px; }
        .libros-table thead tr { background: #1a3a6e; color: #fff; }
        .libros-table thead th { padding: 5px 6px; text-align: left; font-weight: bold; letter-spacing: 0.02em; }
        .libros-table thead th.center { text-align: center; }
        .libros-table tbody tr:nth-child(even) { background: #f5f7fc; }
        .libros-table tbody td { padding: 5px 6px; border-bottom: 0.5px solid #dde3f0; color: #222; }
        .libros-table tbody td.center { text-align: center; }

        /* ─── OBSERVACIONES ─── */
        .obs-box {
            border: 1px solid #c8d0e0;
            border-radius: 3px;
            padding: 8px 10px;
            font-size: 8.5pt;
            color: #444;
            background: #fafbff;
            min-height: 32px;
            margin-top: 6px;
        }

        /* ─── NOTA ─── */
        .nota-box {
            border-left: 4px solid #1a3a6e;
            padding: 6px 10px;
            background: #f0f4ff;
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
        .firma-line { border-top: 1px solid #1a1a2e; margin: 0 10px; }
        .firma-label { font-size: 7.5pt; color: #333; margin-top: 4px; line-height: 1.4; }
        .firma-label strong { display: block; font-size: 7pt; color: #666; }

        /* ─── PIE ─── */
        .footer {
            margin-top: 16px;
            border-top: 1.5px solid #1a3a6e;
            padding-top: 6px;
            display: table;
            width: 100%;
            font-size: 7.5pt;
            color: #555;
        }
        .footer-left { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }
        .footer strong { color: #1a3a6e; }

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
        <div class="doc-main">Constancia de Entrega de Textos</div>
        <div class="doc-code">{{ $constanciaCodigo }}</div>
    </div>

    {{-- ══════════════ DATOS DEL ESTUDIANTE ══════════════ --}}
    <div class="section-title">Datos del Estudiante</div>
    <table class="datos-table">
        <tr>
            <td class="lbl">Código de Estudiante</td>
            <td class="val">{{ $entrega->estudiante->codigo_estudiante ?? '-' }}</td>
            <td class="lbl">DNI</td>
            <td class="val">{{ $entrega->estudiante->dni ?? '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">Apellidos y Nombres</td>
            <td class="val" colspan="3">
                {{ strtoupper(($entrega->estudiante->apellidos ?? '') . ', ' . ($entrega->estudiante->nombres ?? '')) }}
            </td>
        </tr>
        <tr>
            <td class="lbl">Nivel Educativo</td>
            <td class="val">{{ ucfirst($entrega->estudiante->nivel ?? '-') }}</td>
            <td class="lbl">Grado</td>
            <td class="val">{{ $entrega->estudiante->grado ?? '-' }}° Grado</td>
        </tr>
        <tr>
            <td class="lbl">Sección</td>
            <td class="val">{{ strtoupper($entrega->estudiante->seccion ?? '-') }}</td>
            <td class="lbl">Año Escolar</td>
            <td class="val">{{ $entrega->anioEscolar->anio ?? date('Y') }}</td>
        </tr>
        <tr>
            <td class="lbl">Apoderado / Representante</td>
            <td class="val" colspan="3">
                @if($entrega->tipo_firmante === 'apoderado' && $entrega->padre)
                    {{ strtoupper($entrega->padre->nombre_completo ?? ($entrega->padre->apellidos . ', ' . $entrega->padre->nombres)) }}
                    &nbsp;(DNI: {{ $entrega->padre->dni ?? '-' }})
                    &nbsp;— {{ ucfirst($entrega->padre->parentesco ?? 'Apoderado') }}
                @else
                    Recibido personalmente por el estudiante
                @endif
            </td>
        </tr>
        <tr>
            <td class="lbl">Fecha de Entrega</td>
            <td class="val">{{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y') }}</td>
            <td class="lbl">Hora de Entrega</td>
            <td class="val">{{ $entrega->hora_entrega ? \Carbon\Carbon::parse($entrega->hora_entrega)->format('H:i') : '-' }}</td>
        </tr>
    </table>

    {{-- ══════════════ CUERPO ══════════════ --}}
    <div class="section-title">Constancia</div>
    <p class="body-text">
        Por medio de la presente se deja constancia que el/la estudiante antes mencionado(a) ha recibido los textos
        escolares que se detallan a continuación, asignados por el Banco de Libros de la Institución Educativa Emblemática
        Glorioso Colegio Nacional San Carlos para el presente año académico <strong>{{ $entrega->anioEscolar->anio ?? date('Y') }}</strong>,
        bajo el código de registro <strong>{{ $entrega->codigo_general }}</strong>.
    </p>

    {{-- ══════════════ TABLA DE LIBROS ══════════════ --}}
    <div class="section-title">Textos Escolares Entregados</div>
    <table class="libros-table">
        <thead>
            <tr>
                <th style="width:28px">N°</th>
                <th style="width:70px">Código</th>
                <th>Título del Libro / Material</th>
                <th style="width:70px">Área</th>
                <th class="center" style="width:45px">Cant.</th>
                <th style="width:80px">Estado Inicial</th>
            </tr>
        </thead>
        <tbody>
            @forelse($entrega->detalles as $index => $detalle)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $detalle->libro->codigo_libro ?? '-' }}</td>
                    <td>
                        <strong>{{ $detalle->libro->nombre ?? 'Libro no encontrado' }}</strong>
                        @if($detalle->libro->tipo_material ?? false)
                            <br><span style="font-size:7.5pt;color:#666;">{{ ucfirst(str_replace('_', ' ', $detalle->libro->tipo_material)) }}</span>
                        @endif
                    </td>
                    <td>{{ $detalle->libro->area ?? '-' }}</td>
                    <td class="center">{{ $detalle->cantidad ?? 1 }}</td>
                    <td>{{ ucfirst($detalle->entregas ?? 'Bueno') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center" style="color:#999;padding:12px;">Sin libros registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ══════════════ OBSERVACIONES ══════════════ --}}
    <div class="section-title">Observaciones</div>
    <div class="obs-box">
        {{ $entrega->observaciones ?? 'Ninguna.' }}
    </div>

    {{-- ══════════════ NOTA ══════════════ --}}
    <div class="nota-box">
        <strong>NOTA:</strong> El estudiante y su apoderado se comprometen a conservar los textos en buen estado
        y devolverlos al finalizar el periodo escolar correspondiente. El incumplimiento implicará las sanciones
        establecidas en el reglamento institucional del Banco de Libros.
    </div>

    {{-- ══════════════ FIRMAS ══════════════ --}}
    <div class="firmas-grid">
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line"></div>
            <div class="firma-label">
                Firma del Estudiante<br>
                <strong>{{ strtoupper($entrega->estudiante->apellidos ?? '') }}<br>{{ strtoupper($entrega->estudiante->nombres ?? '') }}</strong>
            </div>
        </div>
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line"></div>
            <div class="firma-label">
                Firma del Padre / Madre / Apoderado
                @if($entrega->tipo_firmante === 'apoderado' && $entrega->padre)
                    <strong>{{ strtoupper($entrega->padre->apellidos ?? '') }} {{ strtoupper($entrega->padre->nombres ?? '') }}</strong>
                @endif
            </div>
        </div>
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line"></div>
            <div class="firma-label">
                Responsable del Banco de Libros<br>
                <strong>{{ strtoupper($entrega->usuarioRegistro->nombre ?? $entrega->usuarioRegistro->nombres ?? '') }}</strong>
            </div>
        </div>
        <div class="firma-cell">
            <div class="firma-space"></div>
            <div class="firma-line"></div>
            <div class="firma-label">
                Director(a) de la Institución<br>
                <strong>Sello Institucional</strong>
            </div>
        </div>
    </div>

    {{-- ══════════════ PIE DE PÁGINA ══════════════ --}}
    <div class="footer">
        <div class="footer-left">
            <strong>Emitido:</strong> {{ $fechaEmision }} &nbsp;|&nbsp;
            <strong>Código:</strong> {{ $constanciaCodigo }}
        </div>
        <div class="footer-right">
            I.E.E. Glorioso Colegio Nacional San Carlos &nbsp;|&nbsp; Banco de Libros Escolares
        </div>
    </div>

</div>
</body>
</html>
