<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jornada;
use App\Models\Venta;
use App\Models\Producto;
use Carbon\Carbon;

class JornadaController extends Controller
{
    /**
     * Pantalla principal: lista de jornadas + formulario para abrir nueva
     */
    public function index()
    {
        $jornadas = Jornada::orderBy('fecha', 'desc')->get();
        $jornadaHoy = Jornada::whereDate('fecha', Carbon::today()->toDateString())->first();
        
        return view('inventario.index', compact('jornadas', 'jornadaHoy'));
    }

    /**
     * Abrir una nueva jornada (CORREGIDO con firstOrCreate)
     */
    public function abrirJornada(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
        ]);

        $fecha = Carbon::parse($request->fecha);
        $fechaString = $fecha->toDateString(); // Formato YYYY-MM-DD
        
        // ✅ CORRECCIÓN 1: Usar whereDate para comparar solo la fecha (ignorar hora)
        $existente = Jornada::whereDate('fecha', $fechaString)->first();
        
        if ($existente) {
            // ✅ Si ya existe, redirigir a esa jornada (NO crear duplicado)
            return redirect()->route('jornada.ver', $existente->id)
                             ->with('info', 'La jornada del ' . $fecha->format('d/m/Y') . ' ya existe. Continuando con esa jornada.');
        }

        // ✅ CORRECCIÓN 2: Usar firstOrCreate para evitar duplicados de todas formas
        try {
            $jornada = Jornada::firstOrCreate(
                ['fecha' => $fechaString],
                [
                    'mes' => $fecha->month,
                    'anio' => $fecha->year,
                    'estado' => 'abierta',
                ]
            );

            $mensaje = $jornada->wasRecentlyCreated 
                ? 'Jornada abierta correctamente para el ' . $fecha->format('d/m/Y')
                : 'La jornada del ' . $fecha->format('d/m/Y') . ' ya existía.';

            return redirect()->route('jornada.ver', $jornada->id)
                             ->with($jornada->wasRecentlyCreated ? 'success' : 'info', $mensaje);
                             
        } catch (\Exception $e) {
            // ✅ Capturar cualquier error inesperado
            return redirect()->route('inventario.index')
                             ->with('error', 'Error al abrir la jornada: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle de una jornada específica
     */
    public function verJornada($id)
    {
        $jornada = Jornada::with('ventas.producto')->findOrFail($id);
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        
        return view('inventario.detalle_jornada', compact('jornada', 'productos'));
    }

    /**
     * Registrar una venta en la jornada
     */
    public function registrarVenta(Request $request)
    {
        $request->validate([
            'jornada_id' => 'required|exists:jornadas,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
            'precio_venta' => 'required|numeric|min:0',
        ]);

        $jornada = Jornada::findOrFail($request->jornada_id);
        
        // Verificar que la jornada esté abierta
        if ($jornada->estaCerrada()) {
            return redirect()->back()->with('error', 'No se puede registrar ventas en una jornada cerrada.');
        }

        $producto = Producto::findOrFail($request->producto_id);

        // Verificar stock suficiente
        if ($producto->cantidad < $request->cantidad) {
            return redirect()->back()->with('error', 'No hay suficiente stock. Stock disponible: ' . $producto->cantidad);
        }

        // Crear la venta
        Venta::create([
            'jornada_id' => $jornada->id,
            'producto_id' => $producto->id,
            'nombre_producto' => $producto->nombre,
            'marca' => $producto->marca,
            'categoria' => $producto->categoria,
            'cantidad' => $request->cantidad,
            'precio_compra' => $producto->precio_compra,
            'precio_venta' => $request->precio_venta,
            'subtotal_venta' => $request->cantidad * $request->precio_venta,
            'subtotal_compra' => $request->cantidad * $producto->precio_compra,
            'ganancia' => ($request->cantidad * $request->precio_venta) - ($request->cantidad * $producto->precio_compra),
        ]);

        // Descontar stock del producto
        $producto->cantidad -= $request->cantidad;
        $producto->save();

        return redirect()->back()->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Eliminar una venta (solo si la jornada está abierta)
     */
    public function eliminarVenta($id)
    {
        $venta = Venta::findOrFail($id);
        $jornada = $venta->jornada;

        if ($jornada->estaCerrada()) {
            return redirect()->back()->with('error', 'No se puede eliminar ventas de una jornada cerrada.');
        }

        // Devolver stock al producto
        $producto = $venta->producto;
        if ($producto) {
            $producto->cantidad += $venta->cantidad;
            $producto->save();
        }

        $venta->delete();

        return redirect()->back()->with('success', 'Venta eliminada correctamente.');
    }

    /**
     * Cerrar la jornada
     */
    public function cerrarJornada($id)
    {
        $jornada = Jornada::findOrFail($id);
        
        if ($jornada->estaCerrada()) {
            return redirect()->back()->with('error', 'La jornada ya está cerrada.');
        }

        $jornada->estado = 'cerrada';
        $jornada->save();

        return redirect()->route('inventario.index')
                         ->with('success', 'Jornada cerrada correctamente. Total ganancia: Bs. ' . number_format($jornada->total_ganancia, 2));
    }

    /**
     * Historial de jornadas agrupadas por mes y año
     */
    public function historial()
    {
        $jornadas = Jornada::orderBy('fecha', 'desc')->get();
        
        // Agrupar por mes y año
        $jornadasPorMes = $jornadas->groupBy(function ($jornada) {
            return $jornada->anio . '-' . str_pad($jornada->mes, 2, '0', STR_PAD_LEFT);
        });

        return view('inventario.historial', compact('jornadasPorMes'));
    }
}