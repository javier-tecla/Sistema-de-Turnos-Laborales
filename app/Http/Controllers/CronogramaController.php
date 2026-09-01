<?php

namespace App\Http\Controllers;

use App\Models\Ausencia;
use App\Models\Cronograma;
use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Turno;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nwidart\Modules\Routing\Controller;

class CronogramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sucursales = Sucursal::all();

        $query = Cronograma::with(['empleado', 'turno', 'sucursal']);
        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }
        $cronogramas = $query->get();

        $eventos = $cronogramas->map(function ($c) {
            return [
                'id' => $c->id,
                'title' => $c->empleado->nombre_completo ?? 'N/A',
                'start' => $c->fecha->format('Y-m-d'),
                'backgroundColor' => $c->turno->color_fondo ?? '#3498db',
                'textColor' => $c->turno->color_texto ?? '#ffffff',
                'empleado_id' => $c->empleado_id,
                'turno_id' => $c->turno_id,
                'sucursal_id' => $c->sucursal_id,
                'turno' => $c->sucursal->nombre ?? 'N/A',
                'horario' => substr($c->turno->hora_inicio, 0, 5).' - '.substr($c->turno->hora_fin, 0, 5),
            ];
        });

        $empleados = Empleado::where('estado', 'activo')->get(['id', 'nombres', 'apellidos']);
        $turnos = Turno::all(['id', 'nombre', 'hora_inicio', 'hora_fin', 'color_fondo', 'color_texto']);

        return view('cronogramas.index', compact('cronogramas', 'eventos', 'sucursales', 'empleados', 'turnos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'turno_id' => 'required|exists:turnos,id',
            'sucursal_id' => 'required|exists:sucursals,id',
            'fecha' => 'nullable|date|after_or_equal:today',
        ]);

        $fechaInicio = Carbon::parse($request->fecha);
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : $fechaInicio->copy();

        $asignados = 0;
        $errores = [];
        $ausencias = [];
        $ausenciaDetalle = null;
        $empleadoNombre = Empleado::find($request->empleado_id)->nombre_completo ?? 'Empleado';

        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            $fechaStr = $fecha->format('Y-m-d');
            $existe = Cronograma::where('empleado_id', $request->empleado_id)
                ->where('fecha', $fechaStr)->exists();

            if ($existe) {
                $errores[] = $fecha->format('d/m/Y');

                continue;
            }

            $ausenteInfo = Ausencia::where('empleado_id', $request->empleado_id)
                ->where('estado', 'aprobado')
                ->where('fecha_inicio', '<=', $fechaStr)
                ->where('fecha_fin', '>=', $fechaStr)
                ->first();

            if ($ausenteInfo) {
                $ausencias[] = $fecha->format('d/m/Y');
                $ausenciaDetalle = $ausenteInfo;

                continue;
            }

            $cronograma = new Cronograma;
            $cronograma->empleado_id = $request->empleado_id;
            $cronograma->turno_id = $request->turno_id;
            $cronograma->sucursal_id = $request->sucursal_id;
            $cronograma->fecha = $fechaStr;
            $cronograma->save();
            $asignados++;
        }

        $mensaje = '';
        if ($asignados > 0) {
            $mensaje = $asignados . ' día(s) asignado(s). ';
        }
        if (count($errores) > 0) {
            $mensaje .= 'Ya tenía turno el: ' . implode(', ', $errores) . '. ';
        }
        if (count($ausencias) > 0 && $ausenciaDetalle) {
            $tipoAusencia = ['vacaciones' => 'Vacaciones', 'medica' => 'Baja médica', 'permiso' => 'Permiso', 'otro' => 'Otro'];
            $mensaje .= $empleadoNombre . ' está de ' . ($tipoAusencia[$ausenciaDetalle->tipo] ?? 'ausencia')
                . ' del ' . $ausenciaDetalle->fecha_inicio->format('d/m/Y')
                . ' al ' . $ausenciaDetalle->fecha_fin->format('d/m/Y') . '.';
            }

            $success = $asignados > 0;

            return response()->json(['success' => $success, 'message' => $mensaje ?: 'No se pudo asignar ningún día.']);

    }

    /**
     * Display the specified resource.
     */
    public function show(Cronograma $cronograma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cronograma $cronograma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cronograma = Cronograma::findOrFail($id);

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'turno_id' => 'required|exists:turnos,id',
            'sucursal_id' => 'required|exists:sucursals,id',
            'fecha' => 'required|date',
        ]);

        $existe = Cronograma::where('empleado_id', $request->empleado_id)
            ->where('fecha', $request->fecha)->where('id', '!=', $id)->exists();

        if ($existe) {
            return response()->json(['success' => false, 'message' => 'El empleado ya tiene turno ese día.'], 422);
        }

        $ausente = Ausencia::where('empleado_id', $request->empleado_id)
            ->where('estado', 'aprobado')
            ->where('fecha_inicio', '<=', $request->fecha)
            ->where('fecha_fin', '>=', $request->fecha)
            ->exists();

        if ($ausente) {
            return response()->json(['success' => false, 'message' => 'El empleado está de ausencia ese día.'], 422);
        }

        $cronograma->empleado_id = $request->empleado_id;
        $cronograma->turno_id = $request->turno_id;
        $cronograma->sucursal_id = $request->sucursal_id;
        $cronograma->fecha = $request->fecha;
        $cronograma->save();

        return response()->json(['success' => true, 'message' => 'Turno actualizado.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        Cronograma::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Asignación eliminada.']);
    }

    public function mover(Request $request, $id)
    {
        $cronograma = Cronograma::findOrFail($id);
        $request->validate(['fecha' => 'required|date']);

        $existe = Cronograma::where('empleado_id', $cronograma->empleado_id)
            ->where('fecha', $request->fecha)->where('id', '!=', $id)->exists();

        if ($existe) {
            return response()->json(['error' => 'El empleado ya tiene un turno ese día.'], 422);
        }

        $ausente = Ausencia::where('empleado_id', $cronograma->empleado_id)
            ->where('estado', 'aprobado')
            ->where('fecha_inicio', '<=', $request->fecha)
            ->where('fecha_fin', '>=', $request->fecha)
            ->exists();

        if ($ausente) {
            return response()->json(['error' => 'El empleado está de ausencia ese día.'], 422);
        }

        $cronograma->fecha = $request->fecha;
        $cronograma->save();

        return response()->json(['success' => true]);
    }

    public function reporte(Request $request)
    {
        $query = Cronograma::with(['empleado', 'turno', 'sucursal']);

        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }
        if ($request->filled('mes')) {
            $query->whereMonth('fecha', $request->mes);
        }
        if ($request->filled('anio')) {
            $query->whereYear('fecha', $request->anio);
        }
        
        $cronogramas = $query->orderBy('fecha')->get();
        $sucursal = $request->filled('sucursal_id')
            ? Sucursal::find($request->sucursal_id)
            : null;

        $porMes = $cronogramas->groupBy(function ($c) {
            return $c->fecha->format('Y-m');
        })->sortKeys();

        $porTurno = $cronogramas->groupBy('turno_id');

        $mesesNombres = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $pdf = Pdf::loadView('cronogramas.reporte', compact('cronogramas', 'sucursal', 'porMes', 'mesesNombres'))
            ->setPaper('letter', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('cronograma-'.($sucursal ? \Str::slug($sucursal->nombre) : 'general').'.pdf');
    }
}
