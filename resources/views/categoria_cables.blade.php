<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cables Eléctricos - Sistema Elinoe</title>
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
        
        .card-table { background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px; max-width: 1400px; position: relative; }
        
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
        
        /* ✅ ESTILO PARA LA FILA DE TOTAL */
        .total-row {
            background-color: #e3f2fd;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #1976d2;
        }
        
        .alerta-insuficiente { color: #d93025; background: #fce8e6; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
        .alerta-disponible { color: #28a745; background: #d4edda; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }

        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal-overlay.active { display: flex; }
        
        .modal-container { background: white; padding: 30px; border-radius: 8px; width: 450px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #1976d2; padding-bottom: 10px; }
        .modal-header h3 { color: #1976d2; font-size: 20px; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #777; }
        .modal-close:hover { color: #333; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #333; }
        .form-group input, .form-group select { width: 100%; padding: 9px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group input:focus, .form-group select:focus { border-color: #1976d2; outline: none; }
        .form-group input[readonly] { background-color: #e3f2fd; font-weight: bold; color: #0d47a1; border: 2px solid #1976d2; }
        
        .form-group small {
            display: block;
            color: #666;
            font-size: 12px;
            margin-top: 4px;
        }
        
        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-cancelar { background-color: #6c757d; color: white; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-cancelar:hover { background-color: #5a6268; }
        .btn-guardar { background-color: #28a745; color: white; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-guardar:hover { background-color: #218838; }
        .btn-eliminar-modal { background-color: #dc3545; color: white; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold; transition: 0.3s; }
        .btn-eliminar-modal:hover { background-color: #c82333; }

        .btn-precios { background-color: #ffc107; color: #333; border: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 5px; }
        .btn-precios:hover { background-color: #e0a800; }

        .btn-eliminar-fila { background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 11px; transition: 0.3s; font-weight: bold; }
        .btn-eliminar-fila:hover { background: #c82333; }

        .alert { padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }
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
                    <h2>Clasificación: Cables Eléctricos</h2>
                    
                    <button type="button" class="btn-accion-categoria btn-agregar" onclick="abrirModalCable()">
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
                            <th>Nombre del cable</th>
                            <th>Marca</th>
                            <th>Cantidad de cajas</th>
                            <th>Precio de compra de caja</th>
                            <th>Precio por metro</th>
                            <th>Precio por caja</th>
                            <th>Acción</th>
                            <th>Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalCajas = 0; @endphp
                        @forelse($productos as $item)
                        @php $totalCajas += $item->cantidad; @endphp
                        <tr>
                            <td><strong>{{ $item->nombre }}</strong></td>
                            <td>{{ $item->marca }}</td>
                            <td><strong>{{ $item->cantidad }}</strong></td>
                            <td>Bs. {{ number_format($item->cable->precio_compra_caja ?? 0, 2) }}</td>
                            <td>Bs. {{ number_format($item->precio_venta ?? 0, 2) }}</td>
                            <td>Bs. {{ number_format($item->cable->precio_venta_caja ?? 0, 2) }}</td>
                            <td>
                                <button type="button" class="btn-precios" onclick="abrirModalPrecios('{{ $item->id }}', '{{ $item->nombre }}', '{{ $item->cable->precio_compra_caja ?? 0 }}')">
                                    <i class="fa-solid fa-tags"></i> Asignar
                                </button>
                                <form action="{{ route('productos.eliminar', $item->id) }}" method="POST" style="display:inline; margin-left:5px;" onsubmit="return confirm('¿Estás seguro de eliminar este cable?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-eliminar-fila">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if($item->cantidad < 5)
                                    <span class="alerta-insuficiente"><i class="fa-solid fa-triangle-exclamation"></i> Producto insuficiente</span>
                                @else
                                    <span class="alerta-disponible"><i class="fa-solid fa-check-circle"></i> Disponible</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: #777; padding: 30px;">
                                <i class="fa-solid fa-plug" style="font-size: 24px; margin-bottom: 10px; display: block; color: #ccc;"></i>
                                No hay cables registrados en el sistema.
                            </td>
                        </tr>
                        @endforelse
                        
                        <!-- ✅ FILA DE TOTAL DE CAJAS -->
                        @if($productos->count() > 0)
                        <tr class="total-row">
                            <td colspan="2" style="text-align: right;">TOTAL DE CAJAS:</td>
                            <td><strong>{{ $totalCajas }}</strong></td>
                            <td colspan="5"></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- ✅ MODAL PARA REGISTRAR CABLES -->
    <div id="modalCable" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3><i class="fa-solid fa-plus-circle" style="color: #28a745;"></i> Registrar Cable Eléctrico</h3>
                <button type="button" class="modal-close" onclick="cerrarModalCable()">&times;</button>
            </div>
            
            <form action="{{ route('cables.store') }}" method="POST" id="formRegistroCable">
                @csrf
                <input type="hidden" name="categoria" value="cables">
                
                <div class="form-group">
                    <label>Nombre del Cable:</label>
                    <input type="text" name="nombre" required placeholder="Ej. Cable THW 12 AWG">
                </div>

                <div class="form-group">
                    <label>Marca:</label>
                    <select name="marca" required>
                        <option value="">Seleccione una marca</option>
                        <option value="COPLAS">🔌 COPLAS</option>
                        <option value="CONDUBOL">🔌 CONDUBOL</option>
                        <option value="Cable Sol">🔌 Cable Sol</option>
                        <option value="Indeco">🔌 Indeco</option>
                        <option value="Tigre">🔌 Tigre</option>
                        <option value="Otra">➕ Otra (Especificar)</option>
                    </select>
                </div>

                <div id="campoOtraMarca" style="display: none;">
                    <div class="form-group">
                        <label>Especificar otra marca:</label>
                        <input type="text" name="otra_marca" placeholder="Escribe el nombre de la marca">
                    </div>
                </div>

                <div class="form-group">
                    <label>Cantidad de cajas:</label>
                    <input type="number" id="cantidad_cajas" name="cantidad_cajas" required placeholder="Ej. 2" min="1" oninput="calcularTotalCable()">
                </div>

                <div class="form-group">
                    <label>Precio de la caja por unidad (Bs.):</label>
                    <input type="number" step="0.01" id="precio_caja" name="precio_caja" required placeholder="0.00" min="0" oninput="calcularTotalCable()">
                </div>

                <div class="form-group">
                    <label>Precio de compra total de cajas (Bs.):</label>
                    <input type="text" id="precio_total" name="precio_total" readonly placeholder="Bs. 0.00">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancelar" onclick="cerrarModalCable()">Cancelar</button>
                    <button type="submit" class="btn-guardar"><i class="fa-solid fa-save"></i> Guardar Cable</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ MODAL PARA ASIGNAR PRECIOS DE VENTA -->
    <div id="modalPrecios" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 id="tituloAsignarPrecios"><i class="fa-solid fa-tags" style="color: #ffc107;"></i> Asignar Precios de Venta</h3>
                <button type="button" class="modal-close" onclick="cerrarModalPrecios()">&times;</button>
            </div>
            
            <form id="formAsignarPrecios" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Referencia Precio Compra Caja:</label>
                    <input type="text" id="ref_precio_compra" readonly style="background-color: #e9ecef; font-weight: bold;">
                </div>

                <div class="form-group">
                    <label>Nuevo Precio de Venta por Metro (Bs.):</label>
                    <input type="number" step="0.01" id="nuevo_precio_metro" name="precio_venta" required placeholder="0.00" min="0">
                </div>

                <div class="form-group">
                    <label>Nuevo Precio de Venta por Caja (Bs.):</label>
                    <input type="number" step="0.01" id="nuevo_precio_caja" name="precio_venta_caja" required placeholder="0.00" min="0">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancelar" onclick="cerrarModalPrecios()">Cancelar</button>
                    <button type="submit" class="btn-guardar"><i class="fa-solid fa-save"></i> Actualizar Precios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ MODAL PARA ELIMINAR PRODUCTO -->
    <div id="modalEliminar" class="modal-overlay">
        <div class="modal-container" style="width: 450px;">
            <div class="modal-header">
                <h3><i class="fa-solid fa-trash" style="color: #dc3545;"></i> Eliminar Cable</h3>
                <button type="button" class="modal-close" onclick="cerrarModalEliminar()">&times;</button>
            </div>
            <p style="margin-bottom: 15px; color: #555;">Selecciona el cable y la cantidad a eliminar:</p>
            
            <form action="{{ route('productos.retirar') }}" method="POST" id="formEliminarProducto">
                @csrf
                
                <div class="form-group">
                    <label>Producto:</label>
                    <select name="producto_id" id="selectEliminar" required style="width:100%; padding:9px; border:1px solid #ddd; border-radius:4px;">
                        <option value="">Seleccione un cable...</option>
                        @foreach($productos as $item)
                            <option value="{{ $item->id }}" data-marca="{{ $item->marca }}" data-stock="{{ $item->cantidad }}">
                                {{ $item->nombre }} - {{ $item->marca }} (Cajas: {{ $item->cantidad }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Marca del Producto:</label>
                    <input type="text" id="marca_eliminar" name="marca" readonly placeholder="Seleccione un producto">
                </div>

                <div class="form-group">
                    <label>Cantidad a Eliminar (cajas):</label>
                    <input type="number" id="cantidad_eliminar" name="cantidad_eliminar" min="1" placeholder="1" required>
                    <small>Stock disponible: <span id="stock_disponible">0</span> cajas</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancelar" onclick="cerrarModalEliminar()">Cancelar</button>
                    <button type="submit" class="btn-eliminar-modal"><i class="fa-solid fa-trash"></i> Eliminar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ========================================
        // MODAL DE REGISTRO DE CABLES
        // ========================================
        function abrirModalCable() {
            document.getElementById('modalCable').style.display = 'flex';
            document.getElementById('modalCable').classList.add('active');
        }

        function cerrarModalCable() {
            document.getElementById('modalCable').style.display = 'none';
            document.getElementById('modalCable').classList.remove('active');
            document.getElementById('modalCable').querySelector('form').reset();
            document.getElementById('precio_total').value = 'Bs. 0.00';
            document.getElementById('campoOtraMarca').style.display = 'none';
        }

        function calcularTotalCable() {
            let cantidad = parseFloat(document.getElementById('cantidad_cajas').value) || 0;
            let precioUnitario = parseFloat(document.getElementById('precio_caja').value) || 0;
            let total = cantidad * precioUnitario;
            document.getElementById('precio_total').value = 'Bs. ' + total.toFixed(2);
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
        // MODAL DE ASIGNAR PRECIOS
        // ========================================
        function abrirModalPrecios(id, nombre, precioCompra) {
            document.getElementById('tituloAsignarPrecios').innerHTML = '<i class="fa-solid fa-tags" style="color: #ffc107;"></i> Asignar Precios: ' + nombre;
            document.getElementById('ref_precio_compra').value = 'Bs. ' + parseFloat(precioCompra).toFixed(2);
            document.getElementById('formAsignarPrecios').action = '/productos/' + id;
            document.getElementById('modalPrecios').style.display = 'flex';
            document.getElementById('modalPrecios').classList.add('active');
        }

        function cerrarModalPrecios() {
            document.getElementById('modalPrecios').style.display = 'none';
            document.getElementById('modalPrecios').classList.remove('active');
            document.getElementById('formAsignarPrecios').reset();
        }

        // ========================================
        // MODAL DE ELIMINAR PRODUCTO
        // ========================================
        function abrirModalEliminar() {
            document.getElementById('modalEliminar').style.display = 'flex';
            document.getElementById('modalEliminar').classList.add('active');
            document.getElementById('marca_eliminar').value = '';
            document.getElementById('cantidad_eliminar').value = '';
            document.getElementById('stock_disponible').textContent = '0';
            document.getElementById('selectEliminar').selectedIndex = 0;
        }

        function cerrarModalEliminar() {
            document.getElementById('modalEliminar').style.display = 'none';
            document.getElementById('modalEliminar').classList.remove('active');
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
                        alert('No puedes eliminar más de ' + stock + ' cajas.');
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
        // CERRAR MODALES HACIENDO CLICK FUERA
        // ========================================
        window.onclick = function(event) {
            let modalCable = document.getElementById('modalCable');
            let modalPrecios = document.getElementById('modalPrecios');
            let modalEliminar = document.getElementById('modalEliminar');
            
            if (event.target == modalCable) { 
                modalCable.style.display = 'none'; 
                modalCable.classList.remove('active');
            }
            if (event.target == modalPrecios) { 
                modalPrecios.style.display = 'none'; 
                modalPrecios.classList.remove('active');
            }
            if (event.target == modalEliminar) { 
                modalEliminar.style.display = 'none'; 
                modalEliminar.classList.remove('active');
            }
        }

        // ========================================
        // CERRAR MODAL CON TECLA ESC
        // ========================================
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal-overlay').forEach(el => {
                    el.style.display = 'none';
                    el.classList.remove('active');
                });
            }
        });

        // ========================================
        // AUTO-OCULTAR MENSAJES DESPUÉS DE 5 SEGUNDOS
        // ========================================
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(el) {
                el.style.display = 'none';
            });
        }, 5000);
    </script>

</body>
</html>