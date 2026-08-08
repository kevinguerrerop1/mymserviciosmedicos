<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laboratorio;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['roles', 'laboratorio'])->latest()->get();
        $roles = Role::all();
        $laboratorios = Laboratorio::all();

        return view('usuarios.index', compact('usuarios', 'roles', 'laboratorios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,name',
            'laboratorio_id' => 'nullable|required_if:role,laboratorio|exists:laboratorios,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'laboratorio_id' => $request->role === 'laboratorio' ? $request->laboratorio_id : null,
        ]);

        // Asignación de rol vía Spatie
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Usuario registrado correctamente.');
    }
}
