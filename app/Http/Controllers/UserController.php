<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
     // 1. Mostrar lista de usuarios
    public function index()
    {
        // Solo el admin puede ver la lista
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        $usuarios = User::orderBy('id', 'desc')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    // 2. Mostrar formulario para nuevo usuario
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        return view('usuarios.create');
    }

    // 3. Guardar el nuevo usuario con validaciones
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        // Validaciones estrictas
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', 'in:admin,propietario'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], // Pide confirmar password
        ], [
            'name.required'      => 'El nombre completo es obligatorio.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'Debe ingresar un correo electrónico válido.',
            'email.unique'       => 'Este correo ya se encuentra registrado en el sistema.',
            'role.required'      => 'Debe seleccionar un rol para el usuario.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Crear usuario encriptando la contraseña
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('usuarios.index')->with('success', '¡Usuario creado correctamente!');
    }

    // 4. Eliminar un usuario
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        // Evitar que el usuario actual se elimine a sí mismo
        if (Auth::id() == $id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta en sesión.');
        }

        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

}
