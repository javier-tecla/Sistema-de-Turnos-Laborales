<?php

namespace App\Http\Controllers;

use App\Models\Ausencia;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Nwidart\Modules\Routing\Controller;

class AusenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ausencias = Ausencia::with('empleado')->get();

        return view('ausencias.index', compact('ausencias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $empleados = Empleado::where('estado', 'activo')->get();

        return view('ausencias.create', compact('empleados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|in:vacaciones,medica,permiso.otro',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $ausencia = new Ausencia;
        $ausencia->empleado_id = $request->empleado_id;
        $ausencia->tipo = $request->tipo;
        $ausencia->fecha_inicio = $request->fecha_inicio;
        $ausencia->fecha_fin = $request->fecha_fin;
        $ausencia->estado = 'pendiente';
        $ausencia->save();

        return redirect()->route('ausencias.index')->with('success', 'Ausencia registrada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ausencia $ausencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ausencia = Ausencia::findOrFail($id);
        $empleados = Empleado::where('estado', 'activo')->get();
        return view('ausencias.edit', compact('ausencia', 'empleados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ausencia = Ausencia::findOrFail($id);

        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'tipo' => 'required|in:vacaciones,medica,permiso,otro',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:pendiente,aprobado,rechazado',
        ]);

        $ausencia->empleado_id = $request->empleado_id;
        $ausencia->tipo = $request->tipo;
        $ausencia->fecha_inicio = $request->fecha_inicio;
        $ausencia->fecha_fin = $request->fecha_fin;
        $ausencia->estado = $request->estado;
        $ausencia->save();

        return redirect()->route('ausencias.index')->with('success', 'Ausencia actualizada exitosamente.');
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ausencia = Ausencia::findOrFail($id);
        $ausencia->delete();

        return redirect()->route('ausencias.index')->with('success', 'Ausencia eliminada exitosamente.');
    }
}
