<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Entrega</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; }
        .page { padding: 32px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .header img { max-width: 160px; height: auto; }
        .header .institution { text-align: right; }
        .institution h1 { margin: 0; font-size: 20px; letter-spacing: 0.05em; }
        .institution p { margin: 4px 0 0; font-size: 12px; }
        .title { text-align: center; margin: 24px 0; }
        .title h2 { margin: 0; font-size: 24px; letter-spacing: 0.08em; }
        .content { font-size: 13px; line-height: 1.6; }
        .box { border: 1px solid #d1d5db; padding: 16px; margin: 16px 0; }
        .box strong { display: block; margin-bottom: 6px; color: #111827; }
        .details { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .details td { padding: 8px 4px; vertical-align: top; }
        .details td.label { width: 170px; font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #d1d5db; padding: 10px; text-align: left; }
        .table th { background: #f3f4f6; font-weight: bold; }
        .signatures { display: flex; justify-content: space-between; margin-top: 36px; }
        .signature { width: 48%; text-align: center; }
        .signature .line { margin-top: 48px; border-top: 1px solid #111827; width: 100%; }
        .footer { margin-top: 32px; font-size: 11px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <img src="{{ $logo }}" alt="Logo Institución">
            <div class="institution">
                <h1>Institución Educativa Glorioso</h1>
                <p>Av. Ejemplo s/n, I.E.S. Glorioso</p>
                <p>Teléfono: (01) 234-5678</p>
            </div>
        </div>

        <div class="title">
            <h2>CONSTANCIA DE ENTREGA DE TEXTOS</h2>
            <p>Número: <strong>{{ $constanciaCodigo }}</strong></p>
        </div>

        <div class="content">
            <p>La presente constancia certifica que el/la estudiante cuya información se detalla a continuación recibió los textos escolares asignados bajo el registro de entrega.</p>

            <div class="box">
                <strong>Datos del Estudiante</strong>
                <table class="details">
                    <tr>
                        <td class="label">Nombre completo</td>
                        <td>{{ $entrega->estudiante->nombre_completo }}</td>
                    </tr>
                    <tr>
                        <td class="label">Grado / Sección</td>
                        <td>{{ $entrega->estudiante->grado }} - {{ $entrega->estudiante->seccion }}</td>
                    </tr>
                    <tr>
                        <td class="label">Fecha de entrega</td>
                        <td>{{ $entrega->fecha_entrega->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Hora de entrega</td>
                        <td>{{ $entrega->hora_entrega ? \Carbon\Carbon::parse($entrega->hora_entrega)->format('H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Registrado por</td>
                        <td>{{ $entrega->usuarioRegistro->nombre }}</td>
                    </tr>
                </table>
            </div>

            <div class="box">
                <strong>Resumen de Entrega</strong>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Código de entrega</td>
                            <td>{{ $entrega->codigo_general }}</td>
                        </tr>
                        <tr>
                            <td>Total libros entregados</td>
                            <td>{{ $entrega->detalles->count() }}</td>
                        </tr>
                        <tr>
                            <td>Total de libros faltantes</td>
                            <td>{{ $entrega->total_faltantes ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Total de libros dañados</td>
                            <td>{{ $entrega->total_defectuosos ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="box">
                <strong>Detalle de Textos Entregados</strong>
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Título</th>
                            <th>ISBN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entrega->detalles as $index => $detalle)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detalle->libro->titulo }}</td>
                                <td>{{ $detalle->libro->isbn }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p>Se extiende la presente constancia para los fines que correspondan.</p>
        </div>

        <div class="signatures">
            <div class="signature">
                <div class="line"></div>
                <p>Firma del Padre/Madre/Apoderado</p>
            </div>
            <div class="signature">
                <div class="line"></div>
                <p>Firma del Responsable de Entrega</p>
            </div>
        </div>

        <div class="footer">
            <p>Emitido el {{ $fechaEmision }}</p>
        </div>
    </div>
</body>
</html>
