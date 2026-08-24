<x-app-layout :asset="$assets ?? []">
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Listado de Ausencias</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('ausencias.create') }}" class="btn btn-sm btn-primary" role="button">
                                Nueva Ausencia
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="ausencias-table" class="table table-striped text-center w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Empleado</th>
                                        <th>Tipo</th>
                                        <th>Desde</th>
                                        <th>Hasta</th>
                                        <th>Días</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ausencias as $ausencia)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $ausencia->empleado->nombre_completo ?? '-' }}</td>
                                            <td>
                                                @if ($ausencia->tipo === 'vacaciones')
                                                    <span class="badge bg-info">Vacaciones</span>
                                                @elseif ($ausencia->tipo === 'medica')
                                                    <span class="badge bg-warning">Médica</span>
                                                @elseif ($ausencia->tipo === 'permiso')
                                                    <span class="badge bg-secondary">Permiso</span>
                                                @else
                                                    <span class="badge bg-dark">Otro</span>
                                                @endif
                                            </td>
                                            <td>{{ $ausencia->fecha_inicio->format('d/m/Y') }}</td>
                                            <td>{{ $ausencia->fecha_fin->format('d/m/Y') }}</td>
                                            <td>{{ $ausencia->dias }}</td>
                                            <td>
                                                @if ($ausencia->estado === 'pendiente')
                                                    <span class="badge bg-warning">Pendiente</span>
                                                @elseif ($ausencia->estado === 'aprobado')
                                                    <span class="badge bg-success">Aprobado</span>
                                                @else
                                                    <span class="badge bg-danger">Rechazado</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('ausencias.edit', $ausencia->id) }}">
                                                        Editar
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $ausencia->id }}"
                                                        data-nombre="{{ $ausencia->empleado->nombre_completo }}">
                                                        Eliminar
                                                    </button>
                                                    <form action="{{ route('ausencias.destroy', $ausencia->id) }}"
                                                        id="ausencia-delete-{{ $ausencia->id }}" method="post">
                                                        @method('delete')
                                                        @csrf()
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#ausencias-table').DataTable({
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        infoEmpty: "Mostrando 0 a 0 de 0 registros",
                        infoFiltered: "(filtrado de _MAX_ registros totales)",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "No hay datos disponibles en la tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": activar para ordenar ascendente",
                            sortDescending: ": activar para ordenar descendente"
                        }
                    },
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        orderable: false,
                        targets: 2
                    }]
                });

                $(document).on('click', '.btn-delete', function() {
                    var id = $(this).data('id');
                    var nombre = $(this).data('nombre');
                    Swal.fire({
                        title: '¿Eliminar ausencia?',
                        text: '¿Está seguro de eliminar la ausencia de "' + nombre + '"?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3082d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#ausencia-delete-' + id).submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
