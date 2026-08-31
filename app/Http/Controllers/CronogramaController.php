<?php

namespace App\Http\Controllers;

use App\Models\Cronograma;
use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Turno;
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
                'horario' => substr($c->turno->hora_inicio, 0, 5) . ' - ' . substr($c->turno->hora_fin, 0, 5),
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
            'fecha' => 'nullable|date|after_or_equal:fecha',
        ]);

        $fechaInicio = Carbon::parse($request->fecha);
        $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : $fechaInicio->copy();

        $asignados = 0;
        $errores = [];

        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            $fechaStr = $fecha->format('Y-m-d');
            $existe = Cronograma::where('empleado_id', $request->empleado_id)
                ->where('fecha', $fechaStr)->exists();

            if ($existe) {
                $errorres[] = $fecha->format('d/m/Y');
                continue;
            }

            $cronograma = new Cronograma();
            $cronograma->empleado_id = $request->empleado_id;
            $cronograma->turno_id = $request->turno_id;
            $cronograma->sucursal_id = $request->sucursal_id;
            $cronograma->fecha = $fechaStr;
            $cronograma->save();
            $asignados++;
        }

        $mensaje = $asignados . ' día(s) asignado(s).';
        if (count($errores) > 0) {
            $mensaje .= ' Ya existía en: ' . implode(', ', $errores) . '.';
        }

        return response()->json(['success' => true, 'message' => $mensaje]);
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
}
