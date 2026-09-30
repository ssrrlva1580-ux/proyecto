<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function destroy(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // 1. Cierra la sesión del usuario actual
            Auth::logout();

            // 2. Elimina al usuario de la base de datos
            $user->delete();

            // 3. Invalida la sesión web por seguridad
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // 4. Redirige al login con un mensaje
        return redirect('/login')->with('success', 'Tu cuenta ha sido eliminada correctamente.');

        ///////pruba
    }
}