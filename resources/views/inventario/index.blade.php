<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; }
        .sidebar { width: 250px; background-color: #1976d2; color: #ffffff; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 22px; font-weight: bold; display: flex; align-items: center; gap: 10px; background-color: #1565c0; }
        .sidebar-menu { list-style: none; padding-top: 10px; flex: 1; }
        .sidebar-menu li a { display: flex; align-items: center; gap: 15px; padding: 15px 20px; color: #ffffff; text-decoration: none; font-size: 15px; }
        .sidebar-menu li.active a { background-color: #ffffff; color: #1976d2; border-radius: 20px 0 0 20px; margin-left: 10px; }
        .sidebar-menu li a:hover:not(.active) { background-color: #1565c0; }
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { height: 60px; background-color: #ffffff; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .content-area { padding: 30px; }
        
        .card { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px; margin-bottom: 20px; }
        .card h2 { color: #333; margin-bottom: 20px; font-size: 22px; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 14px; font-weight: bold; margin-bottom: 5px; color: #333; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        
        .btn { padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary { background-color: #1976d2; color: white; }
        .btn-primary:hover { background-color: #1565c0; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-success:hover { background-color: #218838; }
        .btn-info { background-color: #17a2b8; color: white; }
        .btn-info:hover { background-color: #138496; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        
        .alert { padding: 12px 20px; border-radius: 4px; margin-bottom: 15px; font-weight: bold; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        
        .badge { padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-abierta { background: #28a745; color: white; }
        .badge-cerrada { background: #6c757d; color: white; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-bolt"></i><span>Sistema Elinoe</span></div>
        <ul class="sidebar-menu">
            <li><a href="/dashboard"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <li><a href="#"><i class="fa-solid fa-users"></i> Clientes</a></li>
            <li><a href="/proveedores"><i class="fa-solid fa-building"></i> Proveedores</a></li>
            <li><a href="/inventario" class="active"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a></li>
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
                <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info"><i class="fa-solid fa-info-circle"></i> {{ session('info') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-warning"><i class="fa-solid fa-exclamation-triangle"></i> {{ session('error') }}</div>
            @endif

            <!-- FORMULARIO ABRIR JORNADA -->
            <div class="card">
                <h2><i class="fa-solid fa-calendar-plus" style="color: #1976d2;"></i> Abrir Jornada del Día</h2>
                <form action="{{ route('jornada.abrir') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Fecha de la jornada:</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-play"></i> Abrir Jornada
                    </button>
                </form>
            </div>

            <!-- JORNADA DE HOY -->
            @if($jornadaHoy)
            <div class="card" style="background: #e3f2fd;">
                <h2><i class="fa-solid fa-calendar-day" style="color: #1976d2;"></i> Jornada de Hoy</h2>
                <p><strong>Fecha:</strong> {{ $jornadaHoy->fecha->format('d/m/Y') }}</p>
                <p><strong>Estado:</strong> <span class="badge badge-{{ $jornadaHoy->estado }}">{{ ucfirst($jornadaHoy->estado) }}</span></p>
                <p><strong>Total ventas:</strong> Bs. {{ number_format($jornadaHoy->total_ventas, 2) }}</p>
                <p><strong>Total ganancia:</strong> Bs. {{ number_format($jornadaHoy->total_ganancia, 2) }}</p>
                <br>
                <a href="{{ route('jornada.ver', $jornadaHoy->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-eye"></i> Ver Jornada
                </a>
            </div>
            @endif

            <!-- HISTORIAL DE JORNADAS -->
            <div class="card">
                <h2><i class="fa-solid fa-list" style="color: #1976d2;"></i> Jornadas Registradas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Mes</th>
                            <th>Año</th>
                            <th>Total Ventas</th>
                            <th>Total Ganancia</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jornadas as $jornada)
                        <tr>
                            <td>{{ $jornada->fecha->format('d/m/Y') }}</td>
                            <td>{{ $jornada->nombre_mes }}</td>
                            <td>{{ $jornada->anio }}</td>
                            <td>Bs. {{ number_format($jornada->total_ventas, 2) }}</td>
                            <td>Bs. {{ number_format($jornada->total_ganancia, 2) }}</td>
                            <td><span class="badge badge-{{ $jornada->estado }}">{{ ucfirst($jornada->estado) }}</span></td>
                            <td>
                                <a href="{{ route('jornada.ver', $jornada->id) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #999;">
                                No hay jornadas registradas aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <br>
                <a href="{{ route('jornada.historial') }}" class="btn btn-info">
                    <i class="fa-solid fa-history"></i> Ver Historial por Mes
                </a>
            </div>

        </main>
    </div>

</body>
</html>