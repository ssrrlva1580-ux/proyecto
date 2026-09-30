<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora de Compras - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 20px; color: #333; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        h1 { color: #1976d2; margin: 0; font-size: 26px; }
        .btn-back { background: #6c757d; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: bold; font-size: 14px; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr 120px 120px; gap: 15px; align-items: end; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #475569; }
        .form-group select, .form-group input { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        
        .btn-add { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; height: 41px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #cbd5e1; padding: 12px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: #475569; font-weight: 600; }
        .total-section { text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold; color: #1976d2; }
        .btn-delete { background: #dc3545; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <h1>Calculadora de Compras por Categoría</h1>
        <a href="{{ url('/dashboard') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Volver</a>
    </div>

    <!-- Formulario interactivo -->
    <div class="form-grid">
        <div class="form-group">
            <label>1. Categoría:</label>
            <select id="selectCategoria" onchange="filtrarProductos()">
                <option value="">-- Seleccione categoría --</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}">{{ $cat ?? 'General' }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>2. Producto:</label>
            <select id="selectProducto" onchange="actualizarPrecio()">
                <option value="">-- Seleccione producto --</option>
                <!-- Se llenará dinámicamente mediante JS -->
            </select>
        </div>

        <div class="form-group">
            <label>3. Cantidad:</label>
            <input type="number" id="inputCantidad" value="1" min="1">
        </div>

        <div class="form-group">
            <button type="button" class="btn-add" onclick="agregarAlCarro()"><i class="fa-solid fa-plus"></i> Agregar</button>
        </div>
    </div>

    <!-- Tabla del Ticket / Simulación de Compra -->
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="tablaCalculadoraBody">
            <tr>
                <td colspan="6" style="text-align: center; color: #666;">No hay productos agregados a la calculadora.</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        Total a Pagar: $<span id="spanTotalGeneral">0.00</span>
    </div>
</div>

<!-- Datos de productos en formato JSON para que JavaScript los filtre -->
<script>
    const productosData = @json($productos);
    let carrito = [];

    function filtrarProductos() {
        const categoriaSeleccionada = document.getElementById('selectCategoria').value;
        const selectProd = document.getElementById('selectProducto');
        
        selectProd.innerHTML = '<option value="">-- Seleccione producto --</option>';

        const filtrados = productosData.filter(p => p.categoria === categoriaSeleccionada);
        
        filtrados.forEach(prod => {
            let option = document.createElement('option');
            option.value = prod.id;
            option.setAttribute('data-precio', prod.precio_unidad ?? 0);
            option.setAttribute('data-nombre', prod.nombre);
            option.setAttribute('data-categoria', prod.categoria ?? 'General');
            option.text = prod.nombre + ' (Stock: ' + prod.cantidad + ')';
            selectProd.appendChild(option);
        });
    }

    function agregarAlCarro() {
        const selectProd = document.getElementById('selectProducto');
        const cantidadInput = document.getElementById('inputCantidad');
        
        if (selectProd.value === "") {
            alert("Por favor seleccione un producto.");
            return;
        }

        const optionSelected = selectProd.options[selectProd.selectedIndex];
        const id = selectProd.value;
        const nombre = optionSelected.getAttribute('data-nombre');
        const categoria = optionSelected.getAttribute('data-categoria');
        const precio = parseFloat(optionSelected.getAttribute('data-precio')) || 0;
        const cantidad = parseInt(cantidadInput.value) || 1;
        const subtotal = precio * cantidad;

        // Agregar al arreglo del carrito
        carrito.push({ id, categoria, nombre, precio, cantidad, subtotal });
        
        renderizarTabla();
    }

    function eliminarItem(index) {
        carrito.splice(index, 1);
        renderizarTabla();
    }

    function renderizarTabla() {
        const tbody = document.getElementById('tablaCalculadoraBody');
        tbody.innerHTML = "";

        if (carrito.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: #666;">No hay productos agregados a la calculadora.</td></tr>`;
            document.getElementById('spanTotalGeneral').innerText = "0.00";
            return;
        }

        let totalGeneral = 0;

        carrito.forEach((item, index) => {
            totalGeneral += item.subtotal;
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.categoria}</td>
                <td>${item.nombre}</td>
                <td>$${item.precio.toFixed(2)}</td>
                <td>${item.cantidad}</td>
                <td>$${item.subtotal.toFixed(2)}</td>
                <td><button class="btn-delete" onclick="eliminarItem(${index})"><i class="fa-solid fa-trash"></i></button></td>
            `;
            tbody.appendChild(tr);
        });

        document.getElementById('spanTotalGeneral').innerText = totalGeneral.toFixed(2);
    }
</script>

</body>
</html>