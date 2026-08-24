<x-app-layout :assets="$assets ?? []">
    <div>
        <form action="{{ route('ausencias.update', $ausencia->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Editar Ausencia</h4>
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
                                                {{ old('empleado_id', $ausencia->empleado_id) == $empleado->id ? 'selected' : '' }}>
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
                                        <option value="vacaciones" {{ old('tipo', $ausencia->tipo) === 'vacaciones' ? 'selected' : '' }}>
                                            Vacaciones</option>
                                        <option value="medica" {{ old('tipo', $ausencia->tipo) === 'medica' ? 'selected' : '' }}>Médica
                                        </option>
                                        <option value="permiso" {{ old('tipo', $ausencia->tipo) === 'permiso' ? 'selected' : '' }}>
                                            Permiso</option>
                                        <option value="otro" {{ old('tipo', $ausencia->tipo) === 'otro' ? 'selected' : '' }}>Otro
                                        </option>
                                        @error('tipo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label class="form-label" for="fecha_inicio">Fecha Inicio: <sup
                                            class="text-danger">(*)</sup></label>
                                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                                        value="{{ old('fecha_inicio', $ausencia->fecha_inicio->format('Y-m-d')) }}" required>
                                    @error('fecha_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label class="form-label" for="fecha_inicio">Fecha Fin: <sup
                                            class="text-danger">(*)</sup></label>
                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                                        value="{{ old('fecha_fin', $ausencia->fecha_fin->format('Y-m-d')) }}" required>
                                    @error('fecha_fin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-4">
                                    <label class="form-label" for="estado">Estado:</label>
                                    <select name="estado" id="estado" class="form-select">
                                        <option value="pendiente" 
                                        {{ old('estado', $ausencia->estado) === 'pendiente' ? 'selected' : '' }}>
                                            Pendiente</option>
                                        <option value="aprobado" 
                                        {{ old('estado', $ausencia->estado) === 'aprobado' ? 'selected' : '' }}>
                                        Aprobado</option>
                                        <option value="rechazado" 
                                        {{ old('estado', $ausencia->estado) === 'rechazado' ? 'selected' : '' }}>
                                            Rechazado</option>
                                        </option>
                                        @error('estado')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
