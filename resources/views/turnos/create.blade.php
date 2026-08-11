<x-app-layout :assets="$assets ?? []">
    <div>
        <form action="{{ route('turnos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Nuevo Turno</h4>
                            </div>
                            <div class="card-action">
                                <a href="{{ route('turnos.index') }}" class="btn btn-sm btn-primary"
                                    role="button">Volver</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="categoria_id">Categoría: <sup
                                            class="text-danger">(*)</sup></label>
                                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                                        <option value="">Seleccione una categoría...</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->id }}""
                                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('categoria_id')
                                        <small style="color: red">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="nombre">Nombre del Turno: <sup
                                            class="text-danger">(*)</sup></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control"
                                        placeholder="Ej: Turno Mañana" value="{{ old('nombre') }}" required>
                                    @error('nombre')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label" for="hora_inicio">Hora Inicio: <sup
                                            class="text-danger">(*)</sup></label>
                                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control"
                                        value="{{ old('hora_inicio', '08:00') }}" required>
                                    @error('hora_inicio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label" for="hora_fin">Hora Fin: <sup
                                            class="text-danger">(*)</sup></label>
                                    <input type="time" name="hora_fin" id="hora_fin" class="form-control"
                                        value="{{ old('hora_fin', '16:00') }}" required>
                                    @error('hora_fin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label" for="color_fondo">Color de Fondo:</label>
                                    <input type="color" name="color_fondo" id="color_fondo"
                                        class="form-control form-control-color"
                                        value="{{ old('color_fondo', '#3498db') }}">
                                    @error('color_fondo')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="form-label" for="color_texto">Color de Texto:</label>
                                    <input type="color" name="color_texto" id="color_texto"
                                        class="form-control form-control-color"
                                        value="{{ old('color_texto', '#ffffff') }}">
                                    @error('color_texto')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
