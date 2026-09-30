<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 20px; color: #333; }
        .container { max-width: 1100px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        h1 { color: #1976d2; margin: 0; font-size: 28px; font-weight: 600; }
        
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 14px; }
        .btn-add { background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 8px; font-size: 14px; }
        
        .report-container { border: 1px solid #cbd5e1; border-radius: 6px; margin-top: 15px; background: white; overflow: hidden; }
        .report-title { background: #f8fafc; padding: 14px; font-weight: bold; border-bottom: 1px solid #cbd5e1; display: flex; align-items: center; gap: 8px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #cbd5e1; padding: 14px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; border-top: 1px solid #cbd5e1; }
        .total-row { background-color: #f1f5f9; font-weight: bold; }
        .total-label { text-align: right; padding-right: 20px; }

        /* Estilos del Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 25px; border-radius: 8px; width: 450px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .modal-header { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #1976d2; }
        .modal-form { display: flex; flex-direction: column; gap: 15px; }
        .modal-form select, .modal-form input { padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px; width: 100%; box-sizing: border-box; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px; }
        .btn-cancel { background: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-save { background: #28a745; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <h1>Gestión de Inventario</h1>
        <a href="{{ url('/dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
        <button class="btn-add" id="btnAbrirModal"><i class="fa-solid fa-plus"></i> Más</button>
    </div>

    <div class="report-container">
        <div class="report-title">
            <i class="fa-solid fa-file-invoice-dollar" style="color: #1976d2;"></i> Tablas para reporte:
        </div>

        <table>
            <<thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad Vendida</th> <!-- Nueva columna -->
            <th>Precio De Venta (Total)</th>
            <th>Precio De Importación (Total)</th>
            <th>Ganancia</th>
        </tr>
    </thead>
            <tbody>
                @php 
                    $totalCantidad = 0;
                    $totalVenta = 0; 
                    $totalImportacion = 0; 
                    $totalGanancia = 0; 
                @endphp

                @if($inventarios->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; color: #666;">No hay productos registrados en el inventario.</td>
                    </tr>
                @else
                    @foreach($inventarios as $item)
                    <tr>
            <td>{{ $item->producto }}</td>
            <td>{{ $item->cantidad_vendida ?? 0 }}</td> 
    
    <td>{{ number_format($item->precio_venta, 2) }}</td>
    <td>{{ number_format($item->precio_importacion, 2) }}</td>
    <td>{{ number_format($item->ganancia, 2) }}</td>
        </tr>
                    @php
                        $cant = $item->cantidad ?? 1;
                        $totalCantidad += $cant;
                        $totalVenta += $item->precio_venta;
                        $totalImportacion += $item->precio_importacion;
                        $totalGanancia += $item->ganancia;
                    @endphp
                    @endforeach
                @endif

                <tr class="total-row">
            <td class="total-label" colspan="2">Suma total:</td> <!-- colspan="2" para que ocupe Producto y Cantidad -->
            <td>{{ number_format($inventarios->sum('precio_venta'), 2) }}</td>
            <td>{{ number_format($inventarios->sum('precio_importacion'), 2) }}</td>
            <td>{{ number_format($inventarios->sum('ganancia'), 2) }}</td>
        </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal" id="modalInventario">
    <div class="modal-content">
        <div class="modal-header">Registrar Producto en Inventario</div>
        <form action="{{ route('inventarios.store') }}" method="POST" class="modal-form" autocomplete="off">
            @csrf
            
            <!-- Lista desplegable conectada a los productos de la base de datos -->
            <div>
                <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">Seleccionar Producto:</label>
                <select name="producto" id="selectProducto" required onchange="cargarDatosProducto(this)">
        <option value="">-- Seleccione --</option>
        @foreach($productos as $prod)
            <option value="{{ $prod->nombre }}" data-importacion="{{ $prod->precio_compra }}" data-venta="{{ $prod->precio_unidad }}">
                {{ $prod->nombre }}
            </option>
        @endforeach
    </select>
            </div>

            <!-- Nueva sección para anotar la cantidad de productos vendidos -->
            
            <div>
    <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">Cantidad Vendida:</label>
    <!-- ASEGÚRATE DE QUE EL NAME SEA EXACTAMENTE ESTE -->
    <input type="number" name="cantidad_vendida" id="inputCantidad" placeholder="Ej. 2" min="1" required>
</div>

            <div>
                <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">Precio de Importación:</label>
                <input type="number" step="0.01" name="precio_importacion" id="inputImportacion" required>
            </div>

            <div>
                <label style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 5px;">Precio de Venta:</label>
                <input type="number" step="0.01" name="precio_venta" id="inputVenta" placeholder="0.00" required>
            </div>
            
            <div class="modal-actions">
    <!-- El botón de cancelar SÍ debe cerrar el modal -->
    <button type="button" class="btn-cancel" id="btnCerrarModal">Cancelar</button>
    
    <!-- El botón de guardar NO debe cerrar el modal, solo envía el formulario -->
    <button type="submit" class="btn-save">Guardar</button>
</div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modalInventario');
    const btnAbrir = document.getElementById('btnAbrirModal');
    const btnCerrar = document.getElementById('btnCerrarModal');

    // Muestra el formulario al hacer clic en Más
    btnAbrir.addEventListener('click', () => modal.style.display = 'flex');
    
    // Oculta el formulario SOLO al dar clic en el botón Cancelar
    btnCerrar.addEventListener('click', () => modal.style.display = 'none');

    /* 
       NOTA: Se removió el evento 'window.addEventListener('click', ...)' 
       para evitar que la ventana flotante se cierre automáticamente al hacer 
       clic fuera de ella. Ahora solo se cerrará con el botón Cancelar o al Guardar.
    */

    // Función para autocompletar precios según la opción seleccionada
    function cargarDatosProducto(select) {
        const optionSelected = select.options[select.selectedIndex];
        const precioImportacion = optionSelected.getAttribute('data-importacion') || '';
        const precioVenta = optionSelected.getAttribute('data-venta') || '';

        document.getElementById('inputImportacion').value = precioImportacion;
        document.getElementById('inputVenta').value = precioVenta;
    }
</script>
</body>
</html>