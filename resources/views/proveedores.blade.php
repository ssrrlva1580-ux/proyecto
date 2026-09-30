<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proveedores - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { background: #f4f6f9; padding: 30px; color: #333; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        h1 { color: #1976d2; margin-bottom: 20px; font-size: 24px; font-weight: 600; }
        
        /* SECCIÓN DE FORMULARIO AMPLIADA */
        .card-body { background: #f8f9fa; padding: 20px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; font-size: 14px; color: #444; }
        .form-control { width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 15px; outline: none; background: #fff; }
        .form-control:focus { border-color: #1976d2; box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.15); }

        .btn-success { background: #28a745; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 15px; display: inline-flex; align-items: center; gap: 6px; transition: background 0.3s; }
        .btn-success:hover { background: #218838; }

        /* BUSCADOR AMPLIADO */
        .search-container { display: flex; gap: 10px; margin-bottom: 25px; }
        .search-container input { flex: 1; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 15px; outline: none; }
        .search-container input:focus { border-color: #1976d2; }
        .btn-primary { background: #1976d2; color: white; border: none; padding: 0 20px; border-radius: 4px; font-weight: bold; font-size: 15px; cursor: pointer; }
        .btn-primary:hover { background: #1565c0; }
        .btn-secondary { background: #6c757d; color: white; border: none; padding: 0 15px; border-radius: 4px; font-weight: bold; text-decoration: none; display: flex; align-items: center; font-size: 14px; }
        .btn-secondary:hover { background: #5a6268; }

        /* ESTILOS DE LA TABLA */
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; background: white; }
        th, td { border: 1px solid #cbd5e1; padding: 14px; text-align: left; font-size: 14px; }
        th { background-color: #1976d2; color: white; font-weight: 600; }
        tr:nth-child(even) { background-color: #f8fafc; }
        
        .btn-danger { background-color: #dc3545; color: white; border: none; padding: 7px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 13px; }
        .btn-danger:hover { background-color: #bd2130; }

        /* BOTÓN SALIR */
        .bottom-actions { display: flex; justify-content: flex-start; margin-top: 15px; }
        .btn-exit { background-color: #d32f2f; color: white; text-decoration: none; padding: 10px 22px; border-radius: 4px; font-weight: bold; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: background 0.3s; }
        .btn-exit:hover { background-color: #b71c1c; }
    </style>
</head>
<body>

<div class="container">
    <h1>Gestión de Proveedores</h1>

    <!-- FORMULARIO DE REGISTRO (Con los names correctos que exige el controlador) -->
    <div class="card-body">
        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nombre completo del proveedor:</label>
                <input type="text" name="nombre_completo" class="form-control" placeholder="Ej. Juan Pérez" required>
            </div>
            <div class="form-group">
                <label>Nombre de la empresa:</label>
                <input type="text" name="nombre_empresa" class="form-control" placeholder="Ej. Eléctrica S.A." required>
            </div>
            <div class="form-group">
                <label>Teléfono o Correo de contacto:</label>
                <input type="text" name="contacto" class="form-control" placeholder="Ej. 70000000 o correo@empresa.com" required>
            </div>
            <button type="submit" class="btn-success">
                <i class="fa-solid fa-plus"></i> Guardar
            </button>
        </form>
    </div>

    <!-- BUSCADOR AMPLIADO -->
    <div class="search-container">
        <form action="{{ route('proveedores.index') }}" method="GET" style="display: flex; width: 100%; gap: 10px;">
            <input type="text" name="search" placeholder="Buscar proveedor por nombre completo..." value="{{ request('search') }}">
            <button class="btn-primary" type="submit">Buscar</button>
            @if(request('search'))
                <a href="{{ route('proveedores.index') }}" class="btn-secondary">Limpiar</a>
            @endif
        </form>
    </div>
        
    <!-- TABLA DE RESULTADOS -->
    <table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Nombre de la Empresa</th>
                <th>Contacto</th>
                <th style="text-align: center; width: 110px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proveedores as $proveedor)
            <tr>
                <td>{{ $proveedor->nombre_completo }}</td>
                <td>{{ $proveedor->nombre_empresa }}</td>
                <td>{{ $proveedor->contacto }}</td>
                <td style="text-align: center;">
                    <form action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este proveedor?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger"><i class="fa-solid fa-trash-can"></i> Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #777; padding: 20px;">No hay proveedores registrados o coincidentes.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- BOTÓN SALIR -->
    <div class="bottom-actions">
        <a href="{{ url('/dashboard') }}" class="btn-exit">
            <i class="fa-solid fa-arrow-left-long"></i> Salir
        </a>
    </div>
</div>

</body>
</html>