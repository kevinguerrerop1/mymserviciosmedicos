<?php

namespace App\Http\Controllers;

use App\Models\ComentarioExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioExamenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'examen_id'  => 'required|exists:examenes,id',
            'comentario' => 'required|string|max:1000',
        ]);

        ComentarioExamen::create([
            'examen_id'  => $request->examen_id,
            'user_id'    => auth()->id(),
            'comentario' => $request->comentario,
            'tipo'       => 'nota', // <-- Forzamos que la observación del usuario sea de tipo 'nota'
        ]);

        return redirect()->back()->with('success', 'Comentario guardado exitosamente.');
    }
}