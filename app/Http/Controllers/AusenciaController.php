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
        //
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
    public function edit(Ausencia $ausencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ausencia $ausencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ausencia $ausencia)
    {
        //
    }
}
