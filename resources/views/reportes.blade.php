<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        h1 { color: #1976d2; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8fafc; }
    </style>
</head>
<body>

<div class="container">
    <h1>Reporte de Ventas por Categoría</h1>
    
    <table>
    <thead>
        <tr>
            <th>Categoría</th>
            <th>Total Productos Vendidos</th>
            <th>Avisos</th> <!-- NUEVA COLUMNA -->
        </tr>
    </thead>
    <tbody>
        @foreach($ventasPorCategoria as $cat)
        <tr>
            <td>{{ $cat->categoria ?? 'General' }}</td>
            <td>{{ $cat->total_vendido }}</td>
            <td>
                <!-- Condicional: Si es mayor a 10, muestra el aviso; de lo contrario, queda vacío -->
                @if($cat->total_vendido > 10)
                    <span style="color: #2e7d32; font-weight: bold;">Producto más vendido</span>
                @else
                    <span style="color: #888;">-</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

</body>
</html>