<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Laboratorio;
use App\Models\Examen;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with(['roles', 'laboratorio'])->latest()->get();
        $roles = Role::all();
        $laboratorios = Laboratorio::orderBy('nombre', 'asc')->get();

        return view('usuarios.index', compact('usuarios', 'roles', 'laboratorios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|max:255|unique:users',
            'password'       => 'required|string|min:6',
            'role'           => 'required|exists:roles,name',
            'laboratorio_id' => 'nullable|required_if:role,laboratorio|exists:laboratorios,id',
        ]);

        $user = User::create([
            'name'           => trim($request->name),
            'email'          => trim($request->email),
            'password'       => Hash::make($request->password),
            'laboratorio_id' => $request->role === 'laboratorio' ? $request->laboratorio_id : null,
        ]);

        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(User $usuario)
    {
        $usuario->load(['roles', 'laboratorio']);
        $roles = Role::all();
        $laboratorios = Laboratorio::orderBy('nombre', 'asc')->get();

        return view('usuarios.edit', compact('usuario', 'roles', 'laboratorios'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($usuario->id)],
            'password'       => 'nullable|string|min:6',
            'role'           => 'required|exists:roles,name',
            'laboratorio_id' => 'nullable|required_if:role,laboratorio|exists:laboratorios,id',
        ]);

        $datos = [
            'name'           => trim($request->name),
            'email'          => trim($request->email),
            'laboratorio_id' => $request->role === 'laboratorio' ? $request->laboratorio_id : null,
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);
        $usuario->syncRoles([$request->role]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        // 1. Evitar que el administrador se autoelimine
        if (auth()->id() === $usuario->id) {
            return redirect()->back()->with('error', 'No puedes darte de baja a ti mismo.');
        }

        // 2. Proteger integridad referencial si tiene exámenes como patólogo
        if (Examen::where('patologo_id', $usuario->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede dar de baja este usuario porque tiene exámenes asociados como patólogo responsable.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario dado de baja exitosamente.');
    }
}