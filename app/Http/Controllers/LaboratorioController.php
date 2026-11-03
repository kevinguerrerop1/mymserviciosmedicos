<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use App\Models\Examen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaboratorioController extends Controller
{
    public function index()
    {
        $laboratorios = Laboratorio::latest()->get();
        return view('laboratorios.index', compact('laboratorios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'rut'       => 'required|string|max:20|unique:laboratorios,rut',
            'direccion' => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
        ]);

        Laboratorio::create([
            'nombre'    => trim($request->nombre),
            'rut'       => trim($request->rut),
            'direccion' => trim($request->direccion),
            'email'     => $request->email ? trim($request->email) : null,
        ]);

        return redirect()->back()->with('success', 'Laboratorio registrado correctamente.');
    }

    public function update(Request $request, Laboratorio $laboratorio)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'rut'       => ['required', 'string', 'max:20', Rule::unique('laboratorios')->ignore($laboratorio->id)],
            'direccion' => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
        ]);

        $laboratorio->update([
            'nombre'    => trim($request->nombre),
            'rut'       => trim($request->rut),
            'direccion' => trim($request->direccion),
            'email'     => $request->email ? trim($request->email) : null,
        ]);

        return redirect()->route('laboratorios.index')->with('success', 'Laboratorio actualizado correctamente.');
    }

    public function destroy(Laboratorio $laboratorio)
    {
        // 1. Proteger integridad referencial si tiene exámenes asociados
        if (Examen::where('laboratorio_id', $laboratorio->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede dar de baja este laboratorio porque posee exámenes y muestras registradas en el sistema.');
        }

        // 2. Proteger si tiene usuarios vinculados
        if (User::where('laboratorio_id', $laboratorio->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede dar de baja este laboratorio porque existen cuentas de usuario asociadas a él.');
        }

        $laboratorio->delete();

        return redirect()->route('laboratorios.index')->with('success', 'Laboratorio eliminado correctamente.');
    }
}