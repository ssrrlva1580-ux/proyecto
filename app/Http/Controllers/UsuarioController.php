<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Muestra la lista de usuarios
     */
    public function index()
    {
        $usuarios = User::all();
        return view('usuarios', compact('usuarios'));
    }

    /**
     * Procesa y guarda el registro del nuevo usuario tanto en la base de datos como en la sesión.
     */
    public function store(Request $request)
    {
        // Validamos que coincidan las contraseñas y los nombres de usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'username' => 'required|string|max:255|same:username_confirmation',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Guardamos el usuario en la base de datos de forma persistente
        User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]
        );

        // Guardamos también en la sesión para compatibilidad
        session([
            'registered_name' => $request->name,
            'registered_email' => $request->email,
            'registered_username' => $request->username,
            'registered_password' => $request->password,
        ]);

        // Redirigimos al login con éxito
        return redirect()->route('login')->with('success', '¡Cuenta registrada correctamente! Ya puedes iniciar sesión.');
    }

    /**
     * Elimina un usuario por su ID en la base de datos
     */
    public function destroyUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado de la base de datos correctamente.');
    }

    /**
     * Elimina los datos de la sesión del usuario actual (Simula la eliminación de cuenta).
     */
    public function destroy()
    {
        // Limpiamos los datos de sesión registrados
        session()->forget([
            'registered_name', 
            'registered_email', 
            'registered_username', 
            'registered_password'
        ]);
        
        // Redirigimos al login indicando que la cuenta fue eliminada
        return redirect()->route('login')->with('success', 'Usuario eliminado correctamente.');
    }
}