<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Jornadas - Sistema Elinoe</title>
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
        
        .mes-group { margin-bottom: 25px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .mes-header { background: #1976d2; color: white; padding: 15px 20px; font-size: 18px; font-weight: bold; cursor: pointer; display: flex; justify-content: space-between; }
        .mes-header:hover { background: #1565c0; }
        .mes-content { padding: 0; }
        
        .btn { padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .btn-primary { background-color: #1976d2; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        
        .total-row { background: #e3f2fd; font-weight: bold; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-bolt"></i><span>Sistema Elinoe</span></div>
        <ul class="sidebar-menu">
            <li><a href="/dashboard"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <li class="active"><a href="/inventario"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a></li>
            <li><a href="{{ url('/reportes') }}"><i class="fa-solid fa-chart-line"></i> Reportes</a></li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info">angel <i class="fa-solid fa-circle-user" style="font-size: 24px; color: #1976d2;"></i></div>
        </header>

        <main class="content-area">
            <div class="card">
                <h2><i class="fa-solid fa-history" style="color: #1976d2;"></i> Historial de Jornadas por Mes</h2>
                <a href="{{ route('inventario.index') }}" class="btn btn-secondary" style="margin-bottom: 20px;">
                    <i class="fa-solid fa-arrow-left"></i> Volver al Inventario
                </a>

                @forelse($jornadasPorMes as $periodo => $jornadas)
                    @php
                        list($anio, $mes) = explode('-', $periodo);
                        $meses = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
                        $nombreMes = $meses[(int)$mes];
                        $totalVentas = $jornadas->sum('total_ventas');
                        $totalGanancia = $jornadas->sum('total_ganancia');
                    @endphp
                    
                    <div class="mes-group">
                        <div class="mes-header" onclick="toggleMes('mes-{{ $periodo }}')">
                            <span><i class="fa-solid fa-calendar"></i> {{ $nombreMes }} {{ $anio }}</span>
                            <span>
                                Ventas: Bs. {{ number_format($totalVentas, 2) }} | 
                                Ganancia: Bs. {{ number_format($totalGanancia, 2) }}
                            </span>
                        </div>
                        <div id="mes-{{ $periodo }}" class="mes-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Total Ventas</th>
                                        <th>Total Ganancia</th>
                                        <th>Productos</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jornadas as $jornada)
                                    <tr>
                                        <td>{{ $jornada->fecha->format('d/m/Y') }}</td>
                                        <td>Bs. {{ number_format($jornada->total_ventas, 2) }}</td>
                                        <td style="color: #28a745; font-weight: bold;">Bs. {{ number_format($jornada->total_ganancia, 2) }}</td>
                                        <td>{{ $jornada->total_productos_vendidos }}</td>
                                        <td>{{ ucfirst($jornada->estado) }}</td>
                                        <td>
                                            <a href="{{ route('jornada.ver', $jornada->id) }}" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">
                                                <i class="fa-solid fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #999; padding: 30px;">No hay jornadas registradas.</p>
                @endforelse
            </div>
        </main>
    </div>

    <script>
        function toggleMes(id) {
            const el = document.getElementById(id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
    </script>

</body>
</html>