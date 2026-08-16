<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Turno;
use Illuminate\Http\Request;
use Nwidart\Modules\Routing\Controller;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnos = Turno::with('categoria')->get();
        return view('turnos.index', compact('turnos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('turnos.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:150',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'color_fondo' => 'nullable|string|max:7',
            'color_texto' => 'nullable|string|max:7',
        ]);

        Turno::create($request->all());

        return  redirect()->route('turnos.index')->with('success', 'Turno creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $turno = Turno::findOrFail($id);
        $categorias = Categoria::all();
        return view('turnos.edit', compact('turno', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $turno = Turno::findOrFail($id);

        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:150',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'color_fondo' => 'nullable|string|max:7',
            'color_texto' => 'nullable|string|max:7',
        ]);

        $turno->update($request->all());

        return redirect()->route('turnos.index')->with('success', 'Turno actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->delete();

        return redirect()->route('turnos.index')->with('success', 'Turno eliminado exitosamente.');
    }
}
