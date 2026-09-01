<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0 0 4px;
        }

        .header p {
            color: ##666;
            font-size: 10px;
            margin: 0;
        }

        .mes {
            margin-bottom: 15px;
        }

        .mes-titulo {
            background: #333;
            color: #fff;
            padding: 5px 8px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
            padding: 4px 6px;
            border: 1px solid #ddd;
            font-size: 9px;
        }

        td {
            padding: 3px 6px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 10px;
        }

        .semana-titulo {
            background: #e9ecef;
            font-weight: bold;
        }

        .badge-turno {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            color: #fff;
            font-size: 9px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>REPORTE DE CRONOGRAMA DE TURNOS</h1>
        <p>
            {{ $sucursal ? 'Sucursal: ' . $sucursal->nombre : 'Todas las sucursales' }} |
            Generado: {{ now()->format('d/m/Y H:i') }} |
            Total: {{ $cronogramas->count() }} asignaciones
        </p>
    </div>

    @php
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];
    @endphp

    @forelse ($porMes as $mesKey => $itemsMes)
        @php
            $mesNum = (int) substr($mesKey, 5, 2);
            $anio = substr($mesKey, 0, 4);
            $porSemana = $itemsMes
                ->groupBy(function ($c) {
                    return $c->fecha->weekOfYear;
                })
                ->sortKeys(SORT_NATURAL);
        @endphp
        <div class="mes">
            <div class="mes-titulo">{{ $mesesNombres[$mesNum] }} {{ $anio }} - {{ $itemsMes->count() }}
                asignaciones</div>
            @foreach ($porSemana as $semana => $itemsSem)
                <table>
                    <thead>
                        <tr class="semana-titulo">
                            <th colspan="4">Semana {{ $semana }}
                                ({{ $itemsSem->first()->fecha->startOfWeek()->format('d/m/Y') }} -
                                {{ $itemsSem->last()->fecha->endOfWeek()->format('d/m/Y') }})
                            </th>
                        </tr>
                        <tr>
                            <th style="width:130px">Fecha</th>
                            <th>Empleado</th>
                            <th style="width:140px">Turno</th>
                            <th style="width:120px">Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemsSem->groupBy('fecha')->sortKeys() as $fecha => $grupo)
                            @php
                                $first = true;
                                $rowIndex = 0;
                            @endphp
                            @foreach ($grupo->sortBy(function ($c) {
        return $c->empleado->nombre_completo;
    }) as $c)
                                @php
                                    $colores = [
                                        '#f8f9fa',
                                        '#e3f2fd',
                                        '#e8f5e9',
                                        '#fff3e0',
                                        '#fce4ec',
                                        '#f3e5f5',
                                        '#e0f7fa',
                                    ];
                                    $color = $colores[$c->fecha->dayOfWeek];
                                    if ($rowIndex % 2 == 1) {
                                        $color = '#' . dechex(hexdec(substr($color, 1)) - 0x0a0a0a);
                                    }
                                    $rowIndex++;
                                @endphp
                                <tr style="background:{{ $color }}">
                                    @if ($first)
                                        <td rowspan="{{ $grupo->count() }}">{{ $dias[$c->fecha->dayOfWeek] }}
                                            {{ $c->fecha->format('d/m/Y') }}</td>
                                        @php
                                            $first = false;
                                        @endphp
                                    @endif
                                    <td>{{ $c->empleado->nombre_completo ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge-turno"
                                            style="background:{{ $c->turno->color_fondo ?? '#cc' }};color:{{ $c->turno->color_texto ?? '#000' }}">
                                            {{ $c->turno->nombre ?? '-' }}
                                        </span>
                                        <small style="color:#999">{{ substr($c->turno->hora_inicio ?? '', 0, 5) }} -
                                            {{ substr($c->turno->hora_fin ?? '', 0, 5) }}</small>
                                    </td>
                                    <td>{{ $c->sucursal->nombre ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>

    @empty
        <p style="text-align:center; color:#999; padding: 40px;">Sin asignaciones registradas.</p>
    @endforelse

    <div class="footer">
        Reporte generado automáticamente - {{ config('app.name') }}
    </div>
</body>

</html>
