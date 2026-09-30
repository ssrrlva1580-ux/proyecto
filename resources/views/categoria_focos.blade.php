<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Focos - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; }
        .sidebar { width: 250px; background-color: #1976d2; color: #ffffff; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 22px; font-weight: bold; display: flex; align-items: center; gap: 10px; background-color: #1565c0; }
        .sidebar-menu { list-style: none; padding-top: 10px; flex: 1; }
        .sidebar-menu li a { display: flex; align-items: center; gap: 15px; padding: 15px 20px; color: #ffffff; text-decoration: none; font-size: 15px; transition: background-color 0.3s; }
        .sidebar-menu li.active a { background-color: #ffffff; color: #1976d2; border-radius: 20px 0 0 20px; margin-left: 10px; }
        .sidebar-menu li a:hover:not(.active) { background-color: #1565c0; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { height: 60px; background-color: #ffffff; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .content-area { padding: 30px; }
        
        .card-table { background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px; max-width: 1100px; position: relative; }
        .header-accion { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .header-accion .btn-volver { background-color: #6c757d; color: white; border: none; padding: 8px 15px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold; transition: 0.3s; }
        .header-accion .btn-volver:hover { background-color: #5a6268; }
        .header-accion h2 { color: #333; font-size: 22px; margin-right: auto; }
        
        .btn-accion-categoria {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            text-decoration: none;
        }
        .btn-agregar { background-color: #28a745; color: white; }
        .btn-agregar:hover { background-color: #218838; }
        .btn-eliminar { background-color: #dc3545; color: white; }
        .btn-eliminar:hover { background-color: #c82333; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; border: 1px solid #ddd; margin-top: 10px; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; font-size: 14px; }
        th { font-weight: bold; color: #333; background-color: #f8f9fa; }

        .btn-accion { background: none; border: none; cursor: pointer; font-size: 16px; margin-right: 8px; transition: color 0.2s; }
        .btn-ver { color: #1976d2; }
        .btn-ver:hover { color: #1565c0; }

        .precio-compra { color: #d93025; font-weight: bold; }

        .alerta-insuficiente { color: #d93025; background: #fce8e6; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }

        .modal-precio { 
            display: none; 
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%);
            background: white; 
            padding: 30px; 
            border: 1px solid #ccc; 
            box-shadow: 0px 10px 30px rgba(0,0,0,0.3); 
            z-index: 1000; 
            border-radius: 8px; 
            width: 380px; 
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-registro {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border: 1px solid #ccc;
            box-shadow: 0px 10px 30px rgba(0,0,0,0.3);
            z-index: 1000;
            border-radius: 8px;
            width: 420px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-registro h3 {
            margin-bottom: 20px;
            color: #333;
            border-bottom: 2px solid #1976d2;
            padding-bottom: 10px;
        }

        .modal-registro label {
            font-size: 13px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .modal-registro input, .modal-registro select {
            width: 100%;
            margin-bottom: 12px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .modal-registro input:focus, .modal-registro select:focus {
            border-color: #1976d2;
            outline: none;
        }

        .modal-registro input[readonly] {
            background-color: #e3f2fd;
            font-weight: bold;
            color: #0d47a1;
            border: 2px solid #1976d2;
        }

        .modal-registro small {
            display: block;
            color: #666;
            font-size: 12px;
            margin-top: 4px;
        }

        .btn-guardar-producto {
            width: 100%;
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-guardar-producto:hover { background: #218838; }

        .btn-cancelar-registro {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-cancelar-registro:hover { background: #5a6268; }

        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .modal-overlay.active { display: block; }

        .btn-colocar { 
            background: #1976d2; 
            color: white; 
            border: none; 
            padding: 6px 10px; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 12px; 
            transition: 0.3s;
        }
        .btn-colocar:hover { background: #1565c0; }

        .btn-eliminar-fila {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 11px;
            transition: 0.3s;
            font-weight: bold;
        }
        .btn-eliminar-fila:hover { background: #c82333; }

        .btn-eliminar-modal {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-eliminar-modal:hover { background: #c82333; }

        .btn-cancelar {
            width: 100%; 
            margin-top: 5px; 
            padding: 8px; 
            background: #dc3545; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-cancelar:hover { background: #c82333; }

        .btn-guardar {
            width: 100%; 
            background: #28a745; 
            color: white; 
            border: none; 
            padding: 8px; 
            border-radius: 4px; 
            font-weight: bold; 
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-guardar:hover { background: #218838; }

        .modal-precio input {
            width: 100%; 
            margin-bottom: 10px; 
            padding: 8px; 
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .modal-precio label {
            font-size: 13px; 
            font-weight: bold; 
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .costo-compra {
            font-size: 12px; 
            color: #d93025; 
            margin-bottom: 12px; 
            background: #fce8e6; 
            padding: 8px; 
            border-radius: 4px;
            border-left: 3px solid #d93025;
        }

        .stock-normal {
            color: green; 
            font-size: 13px; 
            font-weight: bold;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-bolt"></i><span>Sistema Elinoe</span></div>
        <ul class="sidebar-menu">
            <li class="active"><a href="/dashboard"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <li><a href="#"><i class="fa-solid fa-users"></i> Clientes</a></li>
            <li><a href="/proveedores"><i class="fa-solid fa-building"></i> Proveedores</a></li>
            <li><a href="/productos"><i class="fa-solid fa-box-open"></i> Productos</a></li>
            <li><a href="/inventario"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a></li>
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
            <div class="card-table">
                <div class="header-accion">
                    <a href="/dashboard" class="btn-volver"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                    <h2>Clasificación: Focos</h2>
                    
                    <button type="button" class="btn-accion-categoria btn-agregar" onclick="abrirModalRegistro()">
                        <i class="fa-solid fa-plus"></i> Añadir Producto
                    </button>
                    <button type="button" class="btn-accion-categoria btn-eliminar" onclick="abrirModalEliminar()">
                        <i class="fa-solid fa-trash"></i> Eliminar Producto
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fa-solid fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif
                
                <table>
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Cantidad</th>
                            <th>Precio de Compra x Unidad</th>
                            <th>Unidad</th>
                            <th>Media docena</th>
                            <th>Una docena</th>
                            <th>Observaciones</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $item)
                        <tr>
                            <td><strong>{{ $item->marca }}</strong></td>
                            <td>{{ $item->cantidad }}</td>
                            <td class="precio-compra">Bs. {{ number_format($item->precio_compra ?? 0, 2) }}</td>
                            <td>Bs. {{ number_format($item->precio_unidad ?? 0, 2) }}</td>
                            <td>Bs. {{ number_format($item->precio_media_docena ?? 0, 2) }}</td>
                            <td><strong>Bs. {{ number_format($item->precio_una_docena ?? 0, 2) }}</strong></td>
                            <td>
                                @if($item->cantidad <= 2)
                                    <span class="alerta-insuficiente"><i class="fa-solid fa-triangle-exclamation"></i> Producto insuficiente</span>
                                @else
                                    <span class="stock-normal"><i class="fa-solid fa-check-circle"></i> Stock normal</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn-colocar" onclick="abrirModal({{ $item->id }})">
                                    Colocar precio
                                </button>
                                <form action="{{ route('productos.eliminar', $item->id) }}" method="POST" style="display:inline; margin-left:5px;" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-eliminar-fila">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div id="modal-overlay-{{ $item->id }}" class="modal-overlay" onclick="cerrarModal({{ $item->id }})"></div>

                        <div id="form-{{ $item->id }}" class="modal-precio">
                            <form action="{{ route('productos.update', $item->id) }}" method="POST">
                                @csrf 
                                @method('PUT')
                                <h3 style="margin-bottom:10px; color:#333;">{{ $item->marca ?? $item->nombre }}</h3>
                                
                                <div class="costo-compra">
                                    <i class="fa-solid fa-info-circle"></i> Costo de compra unitario: <strong>Bs. {{ number_format($item->precio_compra, 2) }}</strong>
                                </div>
                                
                                <label>Precio de Compra x Unidad:</label>
                                <input type="number" step="0.01" name="precio_compra" 
                                       value="{{ $item->precio_compra }}" 
                                       style="width:100%; margin-bottom:10px; padding:5px; border: 2px solid #d93025;" required>
                                
                                <label>Precio Unidad (Mín: {{ $item->precio_compra + 0.01 }}):</label>
                                <input type="number" step="0.01" name="precio_unidad" 
                                       min="{{ $item->precio_compra + 0.01 }}" 
                                       value="{{ $item->precio_unidad }}" required>
                                
                                <label>Precio Media Docena (Mín: {{ ($item->precio_compra * 6) + 0.01 }}):</label>
                                <input type="number" step="0.01" name="precio_media_docena" 
                                       min="{{ ($item->precio_compra * 6) + 0.01 }}" 
                                       value="{{ $item->precio_media_docena }}" required>
                                
                                <label>Precio Una Docena (Mín: {{ ($item->precio_compra * 12) + 0.01 }}):</label>
                                <input type="number" step="0.01" name="precio_una_docena" 
                                       min="{{ ($item->precio_compra * 12) + 0.01 }}" 
                                       value="{{ $item->precio_una_docena }}" required>
                                
                                <button type="submit" class="btn-guardar">
                                    <i class="fa-solid fa-save"></i> Guardar Precios
                                </button>
                                
                                <button type="button" class="btn-cancelar" onclick="cerrarModal({{ $item->id }})">
                                    <i class="fa-solid fa-times"></i> Cancelar
                                </button>
                            </form>
                        </div>
                        
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: #777; padding: 30px;">
                                <i class="fa-solid fa-lightbulb" style="font-size: 24px; margin-bottom: 10px; display: block; color: #ccc;"></i>
                                No hay focos registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- ✅ MODAL PARA REGISTRAR NUEVO PRODUCTO -->
    <div id="modal-overlay-registro" class="modal-overlay" onclick="cerrarModalRegistro()"></div>
    <div id="modal-registro" class="modal-registro">
        <h3><i class="fa-solid fa-plus-circle" style="color: #28a745;"></i> Registrar Nuevo Foco</h3>
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <input type="hidden" name="categoria" value="focos">
            
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" placeholder="Ej: Foco LED 9W" required>
            
            <label>Marca:</label>
            <select name="marca" required>
                <option value="">Seleccione una marca</option>
                <option value="Wellmax">💡 Wellmax</option>
                <option value="Mega Star">💡 Mega Star</option>
                <option value="PHILIPS">💡 PHILIPS</option>
                <option value="Westinghouse">💡 Westinghouse</option>
                <option value="Honeywell">💡 Honeywell</option>
                <option value="Opple Iluminación">💡 Opple Iluminación</option>
                <option value="FSL Bolivia">💡 FSL Bolivia</option>
                <option value="Lumismart">💡 Lumismart</option>
                <option value="Witronix LED">💡 Witronix LED</option>
                <option value="Otra">➕ Otra (Especificar)</option>
            </select>
            
            <div id="campoOtraMarca" style="display: none;">
                <label>Especificar otra marca:</label>
                <input type="text" name="otra_marca" placeholder="Escribe el nombre de la marca">
            </div>
            
            <label>Precio de Compra (Bs. por unidad):</label>
            <input type="number" step="0.01" id="precio_compra" name="precio_compra" min="0" placeholder="0.00" required oninput="calcularMontoTotal()">
            
            <label>Cantidad de Productos:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" placeholder="0" required oninput="calcularMontoTotal()">
            
            <label>Monto Total (Bs.):</label>
            <input type="text" id="monto_total" name="monto_total" readonly style="background-color: #e3f2fd; font-weight: bold; color: #0d47a1; border: 2px solid #1976d2;">
            
            <button type="submit" class="btn-guardar-producto">
                <i class="fa-solid fa-save"></i> Guardar Producto
            </button>
            <button type="button" class="btn-cancelar-registro" onclick="cerrarModalRegistro()">
                <i class="fa-solid fa-times"></i> Cancelar
            </button>
        </form>
    </div>

    <!-- ✅ MODAL PARA ELIMINAR PRODUCTO (MEJORADO) -->
    <div id="modal-overlay-eliminar" class="modal-overlay" onclick="cerrarModalEliminar()"></div>
    <div id="modal-eliminar" class="modal-registro" style="width: 450px;">
        <h3><i class="fa-solid fa-trash" style="color: #dc3545;"></i> Eliminar Foco</h3>
        <p style="margin-bottom: 15px; color: #555;">Selecciona el foco y la cantidad a eliminar:</p>
        
        <form action="{{ route('productos.retirar') }}" method="POST" id="formEliminarProducto">
            @csrf
            
            <label>Producto:</label>
            <select name="producto_id" id="selectEliminar" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; margin-bottom:12px;">
                <option value="">Seleccione un producto...</option>
                @foreach($productos as $item)
                    <option value="{{ $item->id }}" data-marca="{{ $item->marca }}" data-stock="{{ $item->cantidad }}">
                        {{ $item->marca }} - {{ $item->nombre ?? 'Sin nombre' }} (Stock: {{ $item->cantidad }})
                    </option>
                @endforeach
            </select>

            <label>Marca del Producto:</label>
            <input type="text" id="marca_eliminar" name="marca" readonly placeholder="Seleccione un producto">

            <label>Cantidad a Eliminar (unidades):</label>
            <input type="number" id="cantidad_eliminar" name="cantidad_eliminar" min="1" placeholder="1" required>
            <small>Stock disponible: <span id="stock_disponible">0</span> unidades</small>
            
            <button type="submit" class="btn-eliminar-modal" style="width:100%; margin-top:10px;">
                <i class="fa-solid fa-trash"></i> Eliminar Producto
            </button>
            <button type="button" class="btn-cancelar-registro" onclick="cerrarModalEliminar()" style="width:100%; margin-top:5px;">
                <i class="fa-solid fa-times"></i> Cancelar
            </button>
        </form>
    </div>

    <script>
        // ========================================
        // MODAL DE PRECIOS
        // ========================================
        function abrirModal(id) {
            document.querySelectorAll('.modal-precio').forEach(el => {
                el.style.display = 'none';
            });
            document.querySelectorAll('.modal-overlay').forEach(el => {
                el.classList.remove('active');
            });
            
            document.getElementById('form-' + id).style.display = 'block';
            document.getElementById('modal-overlay-' + id).classList.add('active');
        }

        function cerrarModal(id) {
            document.getElementById('form-' + id).style.display = 'none';
            document.getElementById('modal-overlay-' + id).classList.remove('active');
        }

        // ========================================
        // MODAL DE REGISTRO DE PRODUCTO
        // ========================================
        function abrirModalRegistro() {
            document.getElementById('modal-registro').style.display = 'block';
            document.getElementById('modal-overlay-registro').classList.add('active');
        }

        function cerrarModalRegistro() {
            document.getElementById('modal-registro').style.display = 'none';
            document.getElementById('modal-overlay-registro').classList.remove('active');
            document.getElementById('modal-registro').querySelector('form').reset();
            document.getElementById('campoOtraMarca').style.display = 'none';
            document.getElementById('monto_total').value = 'Bs. 0.00';
        }

        // ========================================
        // MODAL DE ELIMINAR PRODUCTO (MEJORADO)
        // ========================================
        function abrirModalEliminar() {
            document.getElementById('modal-eliminar').style.display = 'block';
            document.getElementById('modal-overlay-eliminar').classList.add('active');
            document.getElementById('marca_eliminar').value = '';
            document.getElementById('cantidad_eliminar').value = '';
            document.getElementById('stock_disponible').textContent = '0';
            document.getElementById('selectEliminar').selectedIndex = 0;
        }

        function cerrarModalEliminar() {
            document.getElementById('modal-eliminar').style.display = 'none';
            document.getElementById('modal-overlay-eliminar').classList.remove('active');
            document.getElementById('formEliminarProducto').reset();
            document.getElementById('marca_eliminar').value = '';
            document.getElementById('stock_disponible').textContent = '0';
        }

        // ========================================
        // SELECCIÓN DE PRODUCTO - ACTUALIZAR MARCA Y STOCK
        // ========================================
        document.getElementById('selectEliminar')?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const id = this.value;
            
            if (id) {
                const marca = selectedOption.dataset.marca || 'Sin marca';
                const stock = parseInt(selectedOption.dataset.stock) || 0;
                
                document.getElementById('marca_eliminar').value = marca;
                document.getElementById('stock_disponible').textContent = stock;
                
                const inputCantidad = document.getElementById('cantidad_eliminar');
                inputCantidad.min = 1;
                inputCantidad.max = stock;
                inputCantidad.placeholder = '1 a ' + stock;
                
                inputCantidad.addEventListener('input', function() {
                    const valor = parseInt(this.value) || 0;
                    if (valor > stock) {
                        this.value = stock;
                        alert('No puedes eliminar más de ' + stock + ' unidades.');
                    }
                    if (valor < 1) {
                        this.value = 1;
                    }
                });
            } else {
                document.getElementById('marca_eliminar').value = '';
                document.getElementById('stock_disponible').textContent = '0';
                document.getElementById('cantidad_eliminar').value = '';
                document.getElementById('cantidad_eliminar').min = 1;
                document.getElementById('cantidad_eliminar').max = '';
                document.getElementById('cantidad_eliminar').placeholder = '1';
            }
        });

        // ========================================
        // CALCULAR MONTO TOTAL
        // ========================================
        function calcularMontoTotal() {
            const precio = parseFloat(document.getElementById('precio_compra').value) || 0;
            const cantidad = parseInt(document.getElementById('cantidad').value) || 0;
            const total = precio * cantidad;
            document.getElementById('monto_total').value = 'Bs. ' + total.toFixed(2);
        }

        // ========================================
        // MOSTRAR/OCULTAR CAMPO "OTRA MARCA"
        // ========================================
        document.querySelector('select[name="marca"]')?.addEventListener('change', function() {
            const campoOtra = document.getElementById('campoOtraMarca');
            if (this.value === 'Otra') {
                campoOtra.style.display = 'block';
            } else {
                campoOtra.style.display = 'none';
            }
        });

        // ========================================
        // CERRAR MODAL CON TECLA ESC
        // ========================================
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal-precio').forEach(el => {
                    el.style.display = 'none';
                });
                document.querySelectorAll('.modal-overlay').forEach(el => {
                    el.classList.remove('active');
                });
                document.getElementById('modal-registro').style.display = 'none';
                document.getElementById('modal-eliminar').style.display = 'none';
            }
        });

        // ========================================
        // AUTO-OCULTAR MENSAJES
        // ========================================
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(el) {
                el.style.display = 'none';
            });
        }, 5000);
    </script>

</body>
</html>