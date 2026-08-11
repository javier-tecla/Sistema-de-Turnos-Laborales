<x-app-layout :asset="$assets ?? []">
    <div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Listado de Turnos</h4>
                        </div>
                        <div class="card-action">
                            <a href="{{ route('turnos.create') }}" class="btn btn-sm btn-primary" role="button">
                                Nuevo Turno
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0">
                        <div class="table-responsive">
                            <table id="turnos-table" class="table table-striped text-center w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Hora Inicio</th>
                                        <th>Hora Fin</th>
                                        <th>Color</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($turnos as $turno)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $turno->nombre }}</td>
                                            <td>{{ $turno->categoria->nombre ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($turno->hora_inicio)->format('H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($turno->hora_fin)->format('H:i') }}</td>
                                            <td>
                                                <span class="badge px-3 py-2"
                                                    style="background-color: {{ $turno->color_fondo }}; color: {{ $turno->color_texto }};">
                                                    {{ $turno->color_fondo }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('turnos.edit', $turno->id) }}">
                                                        Editar
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                        data-id="{{ $turno->id }}"
                                                        data-nombre="{{ $turno->nombre }}">
                                                        Eliminar
                                                    </button>
                                                    <form action="{{ route('turnos.destroy', $turno->id) }}"
                                                        id="turno-delete-{{ $turno->id }}" method="post">
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
                $('#turnos-table').DataTable({
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
                        title: '¿Eliminar categoria?',
                        text: '¿Está seguro de eliminar "' + nombre + '"?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3082d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#turno-delete-' + id).submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
