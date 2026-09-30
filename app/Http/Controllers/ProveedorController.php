<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// IMPORTANTE: Importamos el modelo Proveedor para poder guardar y borrar en la base de datos
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    /**
     * 1. MUESTRA LA TABLA DE PROVEEDORES
     * Carga todos los registros existentes desde la base de datos y los envía a la vista.
     */
   public function index(Request $request)
{
    $search = $request->input('search');

    // Filtramos si hay texto en el buscador, de lo contrario mostramos todos
    $proveedores = Proveedor::when($search, function ($query, $search) {
        return $query->where('nombre', 'like', '%' . $search . '%');
    })->get();

    return view('proveedores', compact('proveedores'));
}

    /**
     * 2. GUARDA UN NUEVO PROVEEDOR
     * Recibe los datos del formulario (nombre y contacto) y los inserta de forma permanente.
     */
    public function store(Request $request)
    {
        // Validamos que los campos obligatorios contengan datos
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'nombre_empresa'  => 'required|string|max:255',
            'contacto'        => 'required|string|max:255',
        ]);

        // Creamos el registro de forma permanente en la base de datos
        Proveedor::create([
            'nombre_completo' => $request->nombre_completo,
            'nombre_empresa'  => $request->nombre_empresa,
            'contacto'        => $request->contacto,
        ]);

        // Recargamos la página con un mensaje de éxito
        return back()->with('success', '¡Proveedor registrado con éxito!');
    }

    /**
     * 3. ELIMINA UN PROVEEDOR (Botón de Basura)
     * Busca al proveedor mediante su ID único y lo destruye de la base de datos.
     */
    public function destroy($id)
    {
        // Buscamos al proveedor por su ID
        $proveedor = Proveedor::find($id);

        // Si existe, lo eliminamos de forma permanente
        if ($proveedor) {
            $proveedor->delete();
        }

        // Recargamos la página reflejando los cambios de inmediato
        return back()->with('success', 'Proveedor eliminado correctamente.');
    }
}