<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Cable;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all();
        return view('productos', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'categoria'     => 'required|string|max:255',
            'marca'         => 'nullable|string|max:255',
            'medida'        => 'nullable|string|max:255',
            'cantidad'      => 'required|integer|min:1',
            'precio_compra' => 'required|numeric|min:0',
        ]);

        $marca = $request->marca;
        if ($marca === 'Otra' && $request->filled('otra_marca')) {
            $marca = $request->otra_marca;
        }

        if ($request->categoria === 'cables') {
            return $this->storeCable($request);
        }

        Producto::create([
            'nombre'        => $request->nombre,
            'categoria'     => $request->categoria,
            'marca'         => $marca,
            'tipo_medida'   => $request->input('tipo_medida', 'unidad'), 
            'cantidad'      => $request->cantidad,
            'precio_compra' => $request->precio_compra,
            'precio_venta'  => 0,
            'precio_unidad' => 0,
            'precio_media_docena' => 0,
            'precio_una_docena' => 0,
        ]);
        
        $categoria = $request->categoria;
        return redirect()->route('categoria.' . $categoria)
                         ->with('success', '¡Producto registrado correctamente en ' . ucfirst($categoria) . '!');
    }

    /**
     * ✅ MÉTODO storeCable CORREGIDO
     * Verifica si el cable ya existe (mismo nombre y misma marca)
     * Si existe, suma la cantidad de cajas
     * Si no existe, crea un nuevo registro
     */
    public function storeCable(Request $request)
    {
        $request->validate([
            'nombre'         => 'required|string|max:255',
            'marca'          => 'required|string|max:255',
            'cantidad_cajas' => 'required|numeric|min:1',
            'precio_caja'    => 'required|numeric|min:0',
        ]);

        $marca = $request->marca;
        if ($marca === 'Otra' && $request->filled('otra_marca')) {
            $marca = $request->otra_marca;
        }

        // ✅ BUSCAR SI EL CABLE YA EXISTE (mismo nombre y misma marca)
        $cableExistente = Producto::where('nombre', $request->nombre)
                                  ->where('marca', $marca)
                                  ->where('categoria', 'cables')
                                  ->first();

        if ($cableExistente) {
            // ✅ SI EXISTE: Sumar la cantidad de cajas
            $cableExistente->cantidad += $request->cantidad_cajas;
            $cableExistente->save();

            // ✅ Actualizar también el precio de compra de caja si es necesario
            if ($cableExistente->cable) {
                // Si el precio nuevo es diferente, actualizar (opcional)
                if ($cableExistente->cable->precio_compra_caja != $request->precio_caja) {
                    $cableExistente->cable->precio_compra_caja = $request->precio_caja;
                    $cableExistente->cable->save();
                }
            }

            return redirect()->route('categoria.cables')
                             ->with('success', 'Se agregaron ' . $request->cantidad_cajas . ' cajas al cable "' . $request->nombre . '" (Marca: ' . $marca . '). Stock actual: ' . $cableExistente->cantidad);
        }

        // ✅ SI NO EXISTE: Crear nuevo registro
        $producto = Producto::create([
            'nombre'             => $request->nombre,
            'marca'              => $marca,
            'categoria'          => 'cables',
            'cantidad'           => $request->cantidad_cajas, 
            'precio_compra'      => 0,
            'precio_venta'       => 0,
            'precio_unidad'      => 0,
            'precio_media_docena' => 0,
            'precio_una_docena'  => 0,
        ]);

        Cable::create([
            'producto_id'          => $producto->id,
            'precio_compra_caja'   => $request->precio_caja,
            'precio_venta_caja'    => 0,
            'precio_media_docena'  => 0,
            'precio_una_docena'    => 0,
        ]);

        return redirect()->route('categoria.cables')
                         ->with('success', 'Cable "' . $request->nombre . '" registrado correctamente.');
    }

    /**
     * ✅ MÉTODO UPDATE - Maneja todos los tipos de precios
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        
        // Actualizar campos normales de la tabla productos
        if ($request->has('precio_compra')) {
            $producto->precio_compra = $request->precio_compra;
        }
        if ($request->has('precio_unidad')) {
            $producto->precio_unidad = $request->precio_unidad;
        }
        if ($request->has('precio_venta')) {
            $producto->precio_venta = $request->precio_venta;
        }
        if ($request->has('precio_media_docena')) {
            $producto->precio_media_docena = $request->precio_media_docena;
        }
        if ($request->has('precio_una_docena')) {
            $producto->precio_una_docena = $request->precio_una_docena;
        }
        
        $producto->save();

        // Actualizar campos de la tabla de cables si existen
        if ($producto->cable) {
            if ($request->has('precio_venta_caja')) {
                $producto->cable->precio_venta_caja = $request->precio_venta_caja;
                $producto->cable->save();
            }
        }

        return redirect()->back()->with('success', 'Precios actualizados correctamente.');
    }

    /**
     * ✅ MÉTODO ELIMINAR - Elimina un producto completamente
     */
    public function eliminar($id)
    {
        $producto = Producto::findOrFail($id);
        $categoria = $producto->categoria;
        $nombre = $producto->nombre;
        
        if ($producto->categoria === 'cables' && $producto->cable) {
            $producto->cable->delete();
        }
        
        $producto->delete(); 
        
        return redirect()->route('categoria.' . $categoria)
                         ->with('success', 'Producto "' . $nombre . '" eliminado correctamente.');
    }

    /**
     * ✅ MÉTODO RETIRAR STOCK - Retira una cantidad específica desde el modal
     */
    public function retirarStock(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_eliminar' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($request->producto_id);
        $categoria = $producto->categoria;
        $cantidadAEliminar = $request->cantidad_eliminar;

        if ($cantidadAEliminar >= $producto->cantidad) {
            $nombre = $producto->nombre;
            if ($producto->categoria === 'cables' && $producto->cable) {
                $producto->cable->delete();
            }
            $producto->delete();
            $mensaje = 'Producto "' . $nombre . '" eliminado completamente del inventario.';
        } else {
            $producto->cantidad -= $cantidadAEliminar;
            $producto->save();
            $mensaje = 'Se retiraron ' . $cantidadAEliminar . ' unidades del stock. Stock restante: ' . $producto->cantidad;
        }

        return redirect()->route('categoria.' . $categoria)->with('success', $mensaje);
    }
    
    /**
     * ✅ MÉTODO RETIRAR (Alternativo)
     */
    public function retirar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad_eliminar' => 'required|integer|min:1',
        ]);

        $producto = Producto::find($request->producto_id);
        $categoria = $producto->categoria;
        $cantidadAEliminar = $request->cantidad_eliminar;

        if ($cantidadAEliminar >= $producto->cantidad) {
            $nombre = $producto->nombre;
            if ($producto->categoria === 'cables' && $producto->cable) {
                $producto->cable->delete();
            }
            $producto->delete();
            $mensaje = 'Producto "' . $nombre . '" eliminado completamente del inventario.';
        } else {
            $producto->cantidad -= $cantidadAEliminar;
            $producto->save();
            $mensaje = 'Se retiraron ' . $cantidadAEliminar . ' unidades. Stock restante: ' . $producto->cantidad;
        }

        return redirect()->route('categoria.' . $categoria)->with('success', $mensaje);
    }
}