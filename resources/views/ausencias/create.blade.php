<x-app-layout :assets="$assets ?? []">
    <div>
        <form action="{{ route('ausencias.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Nueva Ausencia</h4>
                            </div>
                            <div class="card-action">
                                <a href="{{ route('ausencias.index') }}" class="btn btn-sm btn-primary"
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
                                <div class="form-group col-6">
                                    <label class="form-label" for="empleado_id">Empleado: <sup
                                            class="text-danger">(*)</sup></label>
                                    <select name="empleado_id" id="empleado_id" class="form-select" required>
                                        <option value="">Selecciones un empleado...</option>
                                        @foreach ($empleados as $empleado)
                                            <option value="{{ $empleado->id }}"
                                                {{ old('empleado_id' ? 'selected' : '') }}>
                                                {{ $empleado->nombre_completo }} - {{ $empleado->numero_doc }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('empleado_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label class="form-label" for="tipo">Tipo: <sup
                                            class="text-danger">(*)</sup></label>
                                    <select name="tipo" id="tipo" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="vacaciones" {{ old('tipo') === 'vacaciones' ? 'selected' : '' }}>
                                            Vacaciones</option>
                                        <option value="medica" {{ old('tipo') === 'medica' ? 'selected' : '' }}>Médica
                                        </option>
                                        <option value="permiso" {{ old('tipo') === 'permiso' ? 'selected' : '' }}>
                                            Permiso</option>
                                        <option value="otro" {{ old('tipo') === 'otro' ? 'selected' : '' }}>Otro
                                        </option>
                                        @error('tipo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </select>
                                </div>
                                <div class="form-group col-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio: <sup
                                            class="text-danger">(*)</sup></label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                                        value="{{ old('fecha_inicio') }}" required>
                                    @error('fecha_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-6">
                                    <label class="form-label" for="fecha_inicio">Fecha Fin: <sup
                                            class="text-danger">(*)</sup></label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                        value="{{ old('fecha_fin') }}" required>
                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
