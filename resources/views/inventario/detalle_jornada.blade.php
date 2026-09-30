<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornada {{ $jornada->fecha->format('d/m/Y') }} - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; }
        .sidebar { width: 250px; background-color: #1976d2; color: #ffffff; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 22px; font-weight: bold; display: flex; align-items: center; gap: 10px; background-color: #1565c0; }
        .sidebar-menu { list-style: none; padding-top: 10px; flex: 1; }
        .sidebar-menu li a { display: flex; align-items: center; gap: 15px; padding: 15px 20px; color: #ffffff; text-decoration: none; font-size: 15px; }
        .sidebar-menu li.active a { background-color: #ffffff; color: #1976d2; border-radius: 20px 0 0 20px; margin-left: 10px; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { height: 60px; background-color: #ffffff; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .content-area { padding: 30px; }
        
        .card { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px; margin-bottom: 20px; }
        .card h2 { color: #333; margin-bottom: 20px; font-size: 22px; }
        
        .header-jornada { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .header-jornada h1 { color: #1976d2; font-size: 26px; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-box { background: #f8f9fa; border-left: 4px solid #1976d2; padding: 15px; border-radius: 4px; }
        .stat-box h3 { color: #666; font-size: 13px; margin-bottom: 5px; text-transform: uppercase; }
        .stat-box p { font-size: 22px; font-weight: bold; color: #333; }
        .stat-box.verde { border-left-color: #28a745; }
        .stat-box.azul { border-left-color: #1976d2; }
        .stat-box.naranja { border-left-color: #ff9800; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 14px; font-weight: bold; margin-bottom: 5px; color: #333; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        
        .btn { padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary { background-color: #1976d2; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        
        .alert { padding: 12px 20px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-warning { background: #fff3cd; color: #856404; }
        
        .badge-abierta { background: #28a745; color: white; padding: 5px 10px; border-radius: 12px; font-size: 12px; }
        .badge-cerrada { background: #6c757d; color: white; padding: 5px 10px; border-radius: 12px; font-size: 12px; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-bolt"></i><span>Sistema Elinoe</span></div>
        <ul class="sidebar-menu">
            <li><a href="/dashboard"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <li><a href="/proveedores"><i class="fa-solid fa-building"></i> Proveedores</a></li>
            <li class="active"><a href="/inventario"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a></li>
            <li><a href="{{ url('/reportes') }}"><i class="fa-solid fa-chart-line"></i> Reportes</a></li>
            <li><a href="{{ url('/usuarios') }}"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info">angel <i class="fa-solid fa-circle-user" style="font-size: 24px; color: #1976d2;"></i></div>
        </header>

        <main class="content-area">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning">{{ session('error') }}</div>
            @endif

            <!-- CABECERA DE LA JORNADA -->
            <div class="card">
                <div class="header-jornada">
                    <div>
                        <h1><i class="fa-solid fa-calendar-day"></i> Jornada: {{ $jornada->fecha->format('d/m/Y') }}</h1>
                        <p style="color: #666; margin-top: 5px;">
                            {{ $jornada->nombre_mes }} {{ $jornada->anio }} | 
                            Estado: <span class="badge-{{ $jornada->estado }}">{{ ucfirst($jornada->estado) }}</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-arrow-left"></i> Volver
                        </a>
                        @if($jornada->estaAbierta())
                        <form action="{{ route('jornada.cerrar', $jornada->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de cerrar esta jornada? No podrás modificarla después.');">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-lock"></i> Cerrar Jornada
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- ESTADÍSTICAS -->
                <div class="stats-grid">
                    <div class="stat-box azul">
                        <h3>Total Ventas</h3>
                        <p>Bs. {{ number_format($jornada->total_ventas, 2) }}</p>
                    </div>
                    <div class="stat-box verde">
                        <h3>Total Ganancia</h3>
                        <p>Bs. {{ number_format($jornada->total_ganancia, 2) }}</p>
                    </div>
                    <div class="stat-box naranja">
                        <h3>Productos Vendidos</h3>
                        <p>{{ $jornada->total_productos_vendidos }}</p>
                    </div>
                </div>
            </div>

            <!-- REGISTRAR VENTA (solo si está abierta) -->
            @if($jornada->estaAbierta())
            <div class="card">
                <h2><i class="fa-solid fa-cart-plus" style="color: #28a745;"></i> Registrar Venta</h2>
                <form action="{{ route('venta.registrar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jornada_id" value="{{ $jornada->id }}">
                    
                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 15px; align-items: end;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Producto:</label>
                            <select name="producto_id" id="selectProducto" required onchange="actualizarPrecio()">
                                <option value="">Seleccione un producto...</option>
                                @foreach($productos as $producto)
                                    <option value="{{ $producto->id }}" 
                                            data-precio="{{ $producto->precio_unidad ?? $producto->precio_venta }}"
                                            data-stock="{{ $producto->cantidad }}"
                                            data-nombre="{{ $producto->nombre }}"
                                            data-marca="{{ $producto->marca }}">
                                        {{ $producto->categoria }} - {{ $producto->nombre }} ({{ $producto->marca }}) | Stock: {{ $producto->cantidad }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Cantidad:</label>
                            <input type="number" name="cantidad" id="cantidad" min="1" value="1" required>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label>Precio Venta (Bs.):</label>
                            <input type="number" step="0.01" name="precio_venta" id="precioVenta" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-plus"></i> Registrar
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- TABLA DE VENTAS DEL DÍA -->
            <div class="card">
                <h2><i class="fa-solid fa-list" style="color: #1976d2;"></i> Ventas del Día</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>P. Compra</th>
                            <th>P. Venta</th>
                            <th>Subtotal</th>
                            <th>Ganancia</th>
                            @if($jornada->estaAbierta())
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jornada->ventas as $venta)
                        <tr>
                            <td>{{ $venta->nombre_producto }}</td>
                            <td>{{ $venta->marca }}</td>
                            <td>{{ $venta->categoria }}</td>
                            <td>{{ $venta->cantidad }}</td>
                            <td>Bs. {{ number_format($venta->precio_compra, 2) }}</td>
                            <td>Bs. {{ number_format($venta->precio_venta, 2) }}</td>
                            <td>Bs. {{ number_format($venta->subtotal_venta, 2) }}</td>
                            <td style="color: #28a745; font-weight: bold;">Bs. {{ number_format($venta->ganancia, 2) }}</td>
                            @if($jornada->estaAbierta())
                            <td>
                                <form action="{{ route('venta.eliminar', $venta->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta venta?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $jornada->estaAbierta() ? 9 : 8 }}" style="text-align: center; padding: 30px; color: #999;">
                                No hay ventas registradas en esta jornada.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($jornada->ventas->count() > 0)
                    <tfoot style="background: #e3f2fd; font-weight: bold;">
                        <tr>
                            <td colspan="6" style="text-align: right;">TOTAL:</td>
                            <td>Bs. {{ number_format($jornada->total_ventas, 2) }}</td>
                            <td style="color: #28a745;">Bs. {{ number_format($jornada->total_ganancia, 2) }}</td>
                            @if($jornada->estaAbierta())<td></td>@endif
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

        </main>
    </div>

    <script>
        function actualizarPrecio() {
            const select = document.getElementById('selectProducto');
            const option = select.options[select.selectedIndex];
            const precio = option.dataset.precio || 0;
            document.getElementById('precioVenta').value = precio;
        }
    </script>

</body>
</html>