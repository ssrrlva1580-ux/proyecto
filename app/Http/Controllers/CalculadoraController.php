<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto; // Asegúrate de importar tu modelo de productos

class CalculadoraController extends Controller
{
    public function index()
    {
        // Obtenemos todos los productos para usarlos en la calculadora interactiva
        $productos = Producto::all();
        
        // Extraemos las categorías únicas para el primer selector
        $categorias = $productos->pluck('categoria')->unique();

        return view('calculadora', compact('productos', 'categorias'));
    }
}
