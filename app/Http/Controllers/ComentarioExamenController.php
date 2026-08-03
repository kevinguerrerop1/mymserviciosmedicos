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
            'examen_id' => 'required|exists:examenes,id',
            'comentario' => 'required|string'
        ]);

        ComentarioExamen::create([
            'examen_id' => $request->examen_id,
            'user_id' => Auth::id(),
            'comentario' => $request->comentario
        ]);

        return redirect()->back()->with('success', 'Comentario agregado.');
    }
}