<?php

namespace App\Http\Controllers;

use App\Models\TipoExamen;
use Illuminate\Http\Request;

class TipoExamenController extends Controller
{
    public function index()
    {
        $tipos = TipoExamen::all();
        return view('tipos_examenes.index', compact('tipos'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string']);
        TipoExamen::create($request->only('nombre'));
        return redirect()->back()->with('success', 'Tipo de examen creado.');
    }
}
