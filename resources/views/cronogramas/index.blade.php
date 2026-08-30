<x-app-layout :assets="$assets ?? []">
    <div>
        @if (request('sucursal_id'))
            @php
                $sucursalSeleccionada = $sucursales->find(request('sucursal_id'));
                $turnosSucursal = $cronogramas->pluck('turno')->unique('id');
            @endphp

            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Cronograma</h6>
                        </div>
                        <div class="card-body p-2">
                            <form method="GET">
                                <select name="sucursal_id" class="form-select form-select-sm mb-2"
                                    onchange="this.form.submit()">
                                    @foreach ($sucursales as $suc)
                                        <option value="{{ $suc->id }}"
                                            {{ request('sucursal_id') == $suc->id ? 'selected' : '' }}>
                                            {{ $suc->nombre }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <a href="{{ route('cronogramas.index') }}"
                                class="btn btn-outline-secondary btn-sm w-100 mb-3">
                                Cambiar sucursal
                            </a>

                            <a href="{{ route('cronogramas.reporte', ['sucursal_id' => request('sucursal_id')]) }}"
                                class="btn btn-outline-danger btn-sm w-100 mb-3">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Reporte PDF
                            </a>

                            <div class="d-flex justify-content-between mb-3">
                                <div class="text-center">
                                    <h5 class="mb-0 fw-bold text-success">{{ $cronogramas->count() }}</h5>
                                    <small class="text-muted">Asignados</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="mb-0 fw-bold text-success">{{ $turnosSucursal->count() }}</h5>
                                    <small class="text-muted">Turnos</small>
                                </div>
                            </div>

                            <hr>

                            @foreach ($turnosSucursal as $turno)
                                @php $totalTurno = $cronogramas->where('turno_id', $turno->id)->count(); @endphp
                                <div class="mb-2 border rounded p-2 turno-card"
                                    style="border-left: 4px solid {{ $turno->color_fondo }} !important; cursor: pointer;"
                                    data-turno-id="{{ $turno->id }}" onclick="filtrarPorTurno({{ $turno->id }})">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-bold small">{{ $turno->nombre }}</div>
                                            <div class="text-muted" style="font-size: .7em;">
                                                {{ substr($turno->hora_inicio, 0, 5) }} -
                                                {{ substr($turno->hora_fin, 0, 5) }}
                                            </div>
                                            <small class="fw-bold">{{ $totalTurno }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if ($turnosSucursal->isEmpty())
                                <p class="text-muted mb-0 small">Sin turnos.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Cronograma de {{ $sucursalSeleccionada->nombre }}</h5>
                            <span class="badge bg-primary">{{ $cronogramas->count() }} asignaciones</span>
                        </div>
                        <div class="card-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="bi bi-people me-1"></i>Personal asignado</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $mesesNombres = [
                                    '',
                                    'Enero',
                                    'Febrero',
                                    'Marzo',
                                    'Abril',
                                    'Mayo',
                                    'Junio',
                                    'Julio',
                                    'Agosto',
                                    'Septiembre',
                                    'Octubre',
                                    'Noviembre',
                                    'Diciembre',
                                ];
                                $mesesData = $cronogramas
                                    ->groupBy(function ($c) {
                                        return $c->fecha->format('m');
                                    })
                                    ->sortKeys();
                            @endphp
                            <ul class="nav nav-pills mb-3" id="mesesTab" role="tablist">
                                @foreach ($mesesData as $mes => $items)
                                    @php $anio = $items->first()->fecha->format('Y'); @endphp
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            data-bs-toggle="pill" data-bs-target="#cont-{{ $mes }}"
                                            type="button">
                                            {{ $mesesNombres[(int) $mes] }} {{ $anio }} <span
                                                class="badge bg-secondary ms-1">{{ $items->count() }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content">
                                @foreach ($mesesData as $mes => $itemsMes)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                        id="cont-{{ $mes }}">
                                        @foreach ($itemsMes->pluck('turno')->unique('id') as $turno)
                                            @php $asignados = $itemsMes->where('turno_id', $turno->id); @endphp
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <span class="badge px-3 py-1"
                                                        style="background-color: {{ $turno->color_fondo }}; color: {{ $turno->color_texto }};">{{ $turno->nombre }}
                                                        <small
                                                            class="d-block opacity-75">{{ substr($turno->hora_inicio, 0, 5) }}
                                                            - {{ substr($turno->hora_fin, 0, 5) }}</small>
                                                    </span>
                                                    <span class="badge bg-secondary">{{ $asignados->count() }}</span>
                                                </div>
                                                @foreach ($asignados->groupBy(function ($c) {
            return 'Sem. ' . $c->fecha->weekOfYear;
        })->sortKeys(SORT_NATURAL) as $semana => $itemsSem)
                                                    <div class="ms-2 mb-1">
                                                        <small class="text-muted fw-bold">{{ $semana }}:</small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach ($itemsSem->groupBy('fecha') as $fecha => $grupo)
                                                                @php
                                                                    $fechaObj = $grupo->first()->fecha;
                                                                    $dias = [
                                                                        'Domingo',
                                                                        'Lunes',
                                                                        'Martes',
                                                                        'Miercoles',
                                                                        'Jueves',
                                                                        'Viernes',
                                                                        'Sábado',
                                                                    ];
                                                                @endphp
                                                                <span class="badge bg-light text-dark border px-2 py-1"
                                                                    title="{{ $fechaObj->format('d/m/Y') }}">
                                                                    {{ $dias[$fechaObj->dayOfWeek] }}
                                                                    {{ $fechaObj->format('d/m/Y') }}
                                                                    @foreach ($grupo->sortBy(function ($c) {
        return $c->empleado->nombre_completo;
    }) as $c)
                                                                        <br>
                                                                        <small
                                                                            class="text-muted">{{ $c->empleado->nombre_completo }}</small>
                                                                    @endforeach
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Cronograma</h6>
                        </div>
                        <div class="card-body p-2">
                            <form method="GET">
                                <select name="sucursal_id" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                    <option value="">-- Sucursal --</option>
                                    @foreach ($sucursales as $suc)
                                        <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-building display-1 text-muted"></i>
                            <h4 class="text-muted mt-3">Seleccione una sucursal</h4>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (request('sucursal_id'))
        @push('scripts')
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.21/index.global.min.js"></script>
            <script>
                FullCalendar.globalLocales.push({
                    noEventsText: 'No hay eventos'
                });
            </script>
            <style>
                .fc-daygrid .fc-row {
                    min-height: 50px;
                }

                .fc-daygrid .fc-daygrid-day-frame {
                    padding: 2px !important;
                }

                .fc-event {
                    font-size: .7em;
                    padding: 1px 3px;
                    margin: 1px 2px;
                    border-radius: 3px;
                    cursor: pointer;
                }

                .fc-today {
                    background: #e8f4fd !important;
                }

                .turno-card.active {
                    box-shadow: 0 0 0 2px #000;
                }
            </style>
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                var calendar;

                function filtrarPorTurno(turnoId) {
                    $('.turno-card').removeClass('active');
                    $('.turno-card[data-turno-id="' + turnoId + '"]').addClass('active');
                    calendar.getEvents().forEach(function(event) {
                        var visible = event.extendedProps.turno_id == turnoId;
                        var el = document.querySelector('[data-event-id="' + event.id + '"]');
                        if (el) el.style.opacity = visible ? '1' : '0.3';
                    });
                }

                $(document).ready(function() {
                    calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                        initialView: 'dayGridMonth',
                        locale: 'es',
                        moreLinkText: function(n) {
                            return '+' + n + ' más';
                        },
                        buttonText: {
                            today: 'Hoy',
                            month: 'Mes',
                            week: 'Seman'
                        },
                        editable: true,
                        dayMaxEvents: 3,
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek'
                        },
                        events: @json($eventos),
                        eventContent: function(info) {
                            var e = info.event;
                            var props = e.extendedProps;
                            return {
                                html: '<strong>' + e.title + '</strong><br><small>' + props.turno + ' (' + props
                                    .horario + ')</small>'
                            };
                        },

                        eventDidMount: function(info) {
                            info.el.setAttribute('data-event-id', info.event.id);
                        },
                        eventDrop: function(info) {
                            var event = info.event;
                            Swal.fire({
                                title: '¿Mover',
                                text: '¿Mover a ' + event.start.toLocaleDateString('es-ES') + '?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColo: '#d33',
                                confirmButtonText: 'Sí',
                                cancelButtonText: 'No'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch('{{ url('/cronogramas') }}/' + event.id + '/mover', {
                                        method: 'PUT',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            fecha: event.startStr
                                        })
                                    }).then(r => r.json()).then(d => {
                                        if (d.error) {
                                            info.revert();
                                            Swal.fire('Error', d.error, 'error');
                                        } else Swal.fire('OK', 'Movido.', 'success');
                                    }).catch(() => {
                                        info.revert();
                                        Swal.fire('Error', 'No se pudo mover.', 'error');
                                    });
                                } else info.revert();
                            });
                        },
                        eventClick: function(info) {
                            var event = info.event;
                            var props = event.extendedProps;

                            var empHtml =
                                '<select id="se" class="swal2-select form-select" style="width:100%">';
                            @foreach ($empleados as $emp)
                                empHtml += '<option value="{{ $emp->id }}" ' + ({{ $emp->id }} ==
                                        event.extendedProps.empleado_id ? 'selected' : '') +
                                    '>{{ $emp->nombre_completo }}</option>';
                            @endforeach
                            empHtml += '</select>';

                            var turnHtml = '<select id="st" class="form-select">';
                            @foreach ($turnos as $t)
                                turnHtml += '<option value="{{ $t->id }}" ' + ({{ $t->id }} ==
                                        event.extendedProps.turno_id ? 'selected' : '') +
                                    '>{{ $t->nombre }} ({{ substr($t->hora_inicio, 0, 5) }} - {{ substr($t->hora_fin, 0, 5) }})</option>';
                            @endforeach
                            turnHtml += '</select>';

                            Swal.fire({
                                title: 'Editar',
                                html: '<p class="text-muted mb-3">' + event.title + ' - ' + event.start
                                    .toLocaleDateString('es-ES', {
                                        weekday: 'long',
                                        day: 'numeric',
                                        month: 'numeric',
                                        year: 'numeric'
                                    }) + '</p>' +
                                    '<div class="mb-3"><label class="form-label fw-bold text-start d-block">Empleado:</label>' +
                                    empHtml + '</div>' +
                                    '<div class="mb-3"><label class="form-label fw-bold text-start d-block">Turno:</label>' +
                                    turnHtml + '</div>',
                                showCancelButton: true,
                                showDenyButton: true,
                                confirmButtonColor: '#198765',
                                denyButtonColor: '#dc3545', // Corregido: showDenyButton duplicado cambiado por denyButtonColor
                                confirmButtonText: 'Guardar', // Corregido: confirmButtonColor duplicado cambiado por confirmButtonText
                                denyButtonText: 'Eliminar',
                                cancelButtonText: 'Cancelar',
                                width: '550px',
                                didOpen: function() {
                                    $('#se').select2({
                                        placeholder: 'Buscar...',
                                        dropdownParent: $('.swal2-container')
                                    });
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch('{{ url('/cronogramas') }}/' + event.id, {
                                        method: 'PUT',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            empleado_id: $('#se').val(),
                                            turno_id: $('#st').val(),
                                            sucursal_id: props.sucursal_id,
                                            fecha: event.startStr
                                        })
                                    }).then(r => r.json()).then(d => {
                                        if (d.success) {
                                            Swal.fire('OK', 'Actualizado.', 'success').then(
                                            () => location.reload());
                                        } else {
                                            Swal.fire('Error', d.message, 'error');
                                        }
                                    }); // Corregido: Se eliminó la llave extra que rompía el flujo hacia el else if
                                } else if (result.isDenied) {
                                    Swal.fire({
                                        title: '¿Eliminar?',
                                        text: '¿Eliminar asignación de ' + event.title + '?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        confirmButtonText: 'Sí',
                                        cancelButtonText: 'No'
                                    }).then((del) => {
                                        if (del
                                            .isConfirmed) { // Corregido: del.idConfirmed cambiado a del.isConfirmed
                                            fetch('{{ url('/cronogramas') }}/' + event
                                            .id, { // Corregido: Agregada la coma falta antes de abrir las opciones del fetch
                                                method: 'DELETE',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json'
                                                }
                                            }).then(r => r.json()).then(
                                            d => { // Corregido: Se agregó la captura de respuesta json y se reacomodaron las llaves de cierre
                                                if (d.success || !d.error) {
                                                    Swal.fire('OK', 'Eliminado.',
                                                        'success').then(() =>
                                                        location.reload()
                                                        ); // Corregido: Agregada la coma faltante en Swal.fire
                                                } else {
                                                    Swal.fire('Error', d.error ||
                                                        'No se pudo eliminar.',
                                                        'error');
                                                }
                                            }).catch(() => {
                                                Swal.fire('Error',
                                                    'Error de red al intentar eliminar.',
                                                    'error');
                                            });
                                        }
                                    });
                                }
                            });
                        },
                        dateClick: function(info) {
                            var date = info.date;
                            // Corregido toISIString -> toISOString
                            var dateStr = date.toISOString().split('T')[0];
                            var dateFormatted = date.toLocaleDateString('es-ES', {
                                weekday: 'long',
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });

                            var empHtml =
                                '<select id="swal-emp" class="swal2-select form-select" style="width:100%">';
                            // Removido el caracter '|' huérfano
                            @foreach ($empleados as $emp)
                                empHtml +=
                                    '<option value="{{ $emp->id }}">{{ $emp->nombre_completo }}</option>';
                            @endforeach
                            empHtml += '</select>';

                            var turnHtml = '<select id="swal-turno" class="form-select">';
                            @foreach ($turnos as $t)
                                turnHtml +=
                                    '<option value="{{ $t->id }}">{{ $t->nombre }} ({{ substr($t->hora_inicio, 0, 5) }} - {{ substr($t->hora_fin, 0, 5) }})</option>';
                            @endforeach
                            turnHtml += '</select>'; // Corregida la coma por punto y coma

                            Swal.fire({
                                title: 'Asignar Turno',
                                // Corregido y completado el HTML para el input de fecha de repetición
                                html: '<p class="text-muted mb-3">' + dateFormatted + '</p>' +
                                    '<div class="mb-3"><label class="form-label fw-bold text-start d-block">Empleado:</label>' +
                                    empHtml + '</div>' +
                                    '<div class="mb-3"><label class="form-label fw-bold text-start d-block">Turno:</label>' +
                                    turnHtml + '</div>' +
                                    '<div class="mb-3"><label class="form-label fw-bold text-start d-block">Repetir hasta:</label>' +
                                    '<input type="date" id="swal-fin" class="form-control" min="' +
                                    dateStr + '">' +
                                    '<small class="text-muted d-block text-start mt-1">Vacío = solo este día</small></div>',
                                showCancelButton: true,
                                confirmButtonColor: '#198754', // Corregido el código hexadecimal inválido
                                cancelButtonColor: '#6c757d', // Corregido el typo en cancelButtonColo
                                confirmButtonText: 'Asignar',
                                cancelButtonText: 'Cancelar',
                                width: '550px',
                                didOpen: function() {
                                    $('#swal-emp').select2({
                                        placeholder: 'Buscar...',
                                        dropdownParent: $('.swal2-container')
                                    });
                                },
                                preConfirm: function() {
                                    if (!$('#swal-emp').val()) {
                                        Swal.showValidationMessage('Seleccione un empleado');
                                        return false;
                                    }
                                    return {
                                        empleado_id: $('#swal-emp').val(),
                                        turno_id: $('#swal-turno').val(),
                                        sucursal_id: {{ request('sucursal_id') ?? 'null' }},
                                        fecha: dateStr,
                                        fecha_fin: $('#swal-fin').val() || null
                                    };
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    fetch('{{ route('cronogramas.store') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify(result
                                            .value) // Corregido JSON.stingify -> JSON.stringify
                                    }).then(r => r.json()).then(
                                    d => { // Corregida la coma por un punto antes del .then
                                        if (d.success) {
                                            Swal.fire('OK', d.message ||
                                                'Asignado correctamente.', 'success').then(
                                            () => location.reload());
                                        } else {
                                            Swal.fire('Error', d.message ||
                                                'No se pudo asignar.', 'error');
                                        }
                                    }).catch(() => {
                                        Swal.fire('Error',
                                            'Error de red al procesar la solicitud.',
                                            'error');
                                    });
                                }
                            });
                        }
                    });
                    calendar.render();
                });
            </script>
        @endpush
    @endif

</x-app-layout>
