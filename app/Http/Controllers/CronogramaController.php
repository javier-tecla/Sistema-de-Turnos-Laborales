<?php

namespace App\Http\Controllers;

use App\Models\Cronograma;
use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Turno;
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
        //
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
    public function update(Request $request, Cronograma $cronograma)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cronograma $cronograma)
    {
        //
    }
}
