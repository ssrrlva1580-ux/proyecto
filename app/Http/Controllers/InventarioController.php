<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Inventario; // Asegúrate de tener este
use App\Models\Producto;
class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::all();
        $productos = Producto::all(); 
        
        return view('inventario', compact('inventarios', 'productos'));
    }

    public function store(Request $request)
{
    $request->validate([
        'producto'           => 'required|string',
        'cantidad_vendida'   => 'required|integer|min:1',
        'precio_venta'       => 'required|numeric',
        'precio_importacion' => 'required|numeric',
    ]);

    $gananciaUnitaria = $request->precio_venta - $request->precio_importacion;
    $gananciaTotal = $gananciaUnitaria * $request->cantidad_vendida;

    Inventario::create([
        'producto'           => $request->producto,
        'cantidad_vendida'   => $request->input('cantidad_vendida'), // Captura el valor del formulario
        'precio_venta'       => $request->precio_venta,
        'precio_importacion' => $request->precio_importacion,
        'ganancia'           => $gananciaTotal
    ]);

    return redirect()->back()->with('success', 'Guardado con éxito');
}
public function reportes()
{
    // Obtenemos todas las categorías sumando la cantidad vendida desde la tabla ventas (módulo de jornadas)
    $ventasPorCategoria = DB::table('productos')
        ->leftJoin('ventas', 'productos.id', '=', 'ventas.producto_id')
        ->select('productos.categoria', DB::raw('COALESCE(SUM(ventas.cantidad), 0) as total_vendido'))
        ->groupBy('productos.categoria')
        ->get();

    return view('reportes', compact('ventasPorCategoria'));
}
}
