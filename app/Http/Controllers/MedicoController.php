<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::orderBy('nombre', 'asc')->get();
        return view('medicos.index', compact('medicos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:medicos,nombre',
        ]);

        Medico::create([
            'nombre' => trim($request->nombre),
        ]);

        return redirect()->back()->with('success', 'Médico registrado exitosamente.');
    }

    public function update(Request $request, Medico $medico)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:medicos,nombre,' . $medico->id,
        ]);

        $medico->update([
            'nombre' => trim($request->nombre),
        ]);

        return redirect()->back()->with('success', 'Nombre del médico actualizado.');
    }

    public function destroy(Medico $medico)
    {
        $medico->delete();
        return redirect()->back()->with('success', 'Médico eliminado correctamente.');
    }
}
