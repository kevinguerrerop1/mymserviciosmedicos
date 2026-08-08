<?php

namespace App\Http\Controllers;

use App\Models\Laboratorio;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    public function index()
    {
        $laboratorios = Laboratorio::all();
        return view('laboratorios.index', compact('laboratorios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'rut' => 'required|unique:laboratorios',
            'direccion' => 'required',
            'email' => 'nullable|email'
        ]);

        Laboratorio::create($request->all());
        return redirect()->back()->with('success', 'Laboratorio registrado.');
    }
}
