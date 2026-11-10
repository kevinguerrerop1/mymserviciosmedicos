<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirmaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('herramientas.firma', compact('user'));
    }
}