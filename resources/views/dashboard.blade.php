<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; height: 100vh; background-color: #f4f6f9; }

        /* PANEL LATERAL */
        .sidebar { width: 250px; background-color: #1976d2; color: #ffffff; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; font-size: 22px; font-weight: bold; display: flex; align-items: center; gap: 10px; background-color: #1565c0; }
        .sidebar-menu { list-style: none; padding-top: 10px; flex: 1; }
        .sidebar-menu li a { display: flex; align-items: center; gap: 15px; padding: 15px 20px; color: #ffffff; text-decoration: none; font-size: 15px; transition: background-color 0.3s; }
        .sidebar-menu li.active a { background-color: #ffffff; color: #1976d2; border-radius: 20px 0 0 20px; margin-left: 10px; }
        .sidebar-menu li a:hover:not(.active) { background-color: #1565c0; }
        .logout-btn { background-color: #d32f2f; color: white; text-decoration: none; padding: 15px 20px; display: flex; align-items: center; gap: 15px; font-weight: bold; margin-top: auto; }

        /* CONTENIDO PRINCIPAL */
        .main-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { height: 60px; background-color: #ffffff; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .user-info { display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 15px; }

        .content-area { padding: 30px; }
        
        /* CARD Y TABLA DE RESUMEN */
        .card-table { background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 25px; max-width: 900px; }
        .card-table h2 { margin-bottom: 20px; color: #333; font-size: 20px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; text-align: left; border: 1px solid #ddd; }
        th, td { padding: 15px; border: 1px solid #ddd; font-size: 15px; }
        th { font-weight: bold; color: #333; background-color: #f8f9fa; }
        
        /* ALINEACIONES ESPECÍFICAS DE LA TABLA */
        .col-numero { text-align: center; font-weight: 600; }
        .col-acciones { text-align: center; }

        /* ESTILO DEL BOTÓN VER */
        .btn-ver { background-color: #1976d2; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; transition: 0.3s; font-size: 13px; font-weight: bold; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
        .btn-ver:hover { background-color: #1565c0; }
        
        /* ETIQUETAS DE CANTIDAD */
        .badge { padding: 5px 10px; border-radius: 12px; font-size: 13px; color: white; display: inline-block; text-align: center; min-width: 30px; }
        .badge-verde { background-color: #2e7d32; }
        .badge-rojo { background-color: #c62828; }
        .badge-amarillo { background-color: #f57f17; }

        /* ✅ MENSAJE DE BIENVENIDA O INFORMACIÓN */
        .info-message {
            background: #e3f2fd;
            border-left: 4px solid #1976d2;
            padding: 12px 18px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #0d47a1;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-message i {
            font-size: 18px;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-bolt"></i><span>Sistema Elinoe</span></div>
        <ul class="sidebar-menu">
            <li class="active"><a href="/dashboard"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <li><a href="{{ route('clientes.index') }}"><i class="fa-solid fa-users"></i> Clientes</a></li>
            <li><a href="/proveedores"><i class="fa-solid fa-building"></i> Proveedores</a></li>
            <li><a href="{{ route('calculadora.index') }}"><i class="fa-solid fa-calculator"></i> Calculadora</a></li>
            <li><a href="/inventario"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a></li>
            <li><a href="/configuracion"><i class="fa-solid fa-gear"></i> Configuracion</a></li>
            <li><a href="{{ url('/reportes') }}"><i class="fa-solid fa-chart-line"></i> Reportes</a></li>
            <li><a href="{{ url('/usuarios') }}"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
        </ul>
        <a href="/logout" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            
            <div style="position: relative; display: inline-block;">
                <button id="btnUserMenu" style="background: none; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 600; color: #333;">
                    <span>angel</span>
                    <i class="fa-solid fa-circle-user" style="font-size: 24px; color: #1976d2;"></i>
                </button>

                <div id="userDropdownModal" style="display: none; position: absolute; right: 0; top: 35px; background: white; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); width: 180px; z-index: 1000; padding: 10px;">
                    <p style="margin: 0 0 8px 0; font-size: 12px; color: #64748b; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; text-align: center;">
                        Mi Cuenta
                    </p>
                    <form action="{{ route('usuario.eliminar') }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Serás expulsado al login.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; background: #dc3545; color: white; border: none; padding: 7px; border-radius: 4px; cursor: pointer; font-size: 12px; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 5px;">
                            <i class="fa-solid fa-trash-can"></i> Eliminar Usuario
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="content-area">
            <div class="card-table">
                <h2>📊 Inicio</h2>

                <!-- ✅ MENSAJE INFORMATIVO (OPCIONAL) -->
                <div class="info-message">
                    <i class="fa-solid fa-info-circle"></i>
                    Selecciona una categoría para ver, agregar o eliminar productos.
                </div>

                <!-- ✅ BOTONES ELIMINADOS -->
                <!-- Ya no están los botones "+ Añadir Producto" y "Gestionar / Eliminar Producto" -->

                <table>
                    <tbody>
                        <tr>
                            <td><i class="fa-solid fa-plug" style="color: #1976d2; margin-right: 8px;"></i> Cables eléctricos</td>
                            <td class="col-numero"><span class="badge badge-verde">{{ $cables ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/cables') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-pipe" style="color: #1976d2; margin-right: 8px;"></i> Tubos eléctricos</td>
                            <td class="col-numero"><span class="badge badge-verde">{{ $tubos ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/tubos') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-lightbulb" style="color: #1976d2; margin-right: 8px;"></i> Lámparas</td>
                            <td class="col-numero"><span class="badge badge-verde">{{ $lamparas ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/lamparas') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-plug-circle-plus" style="color: #1976d2; margin-right: 8px;"></i> Sockets</td>
                            <td class="col-numero"><span class="badge badge-verde">{{ $sockets ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/sockets') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-toggle-on" style="color: #1976d2; margin-right: 8px;"></i> Interruptores</td>
                            <td class="col-numero"><span class="badge badge-rojo">{{ $interruptores ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/interruptores') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-tape" style="color: #1976d2; margin-right: 8px;"></i> Cintas aislantes</td>
                            <td class="col-numero"><span class="badge badge-verde">{{ $cintas ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/cintas-aislantes') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-box" style="color: #1976d2; margin-right: 8px;"></i> Cableductos</td>
                            <td class="col-numero"><span class="badge badge-verde">{{ $cableductos ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/cableductos') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-bolt" style="color: #1976d2; margin-right: 8px;"></i> Focos</td>
                            <td class="col-numero"><span class="badge badge-verde">{{ $focos ?? 0 }}</span></td>
                            <td class="col-acciones"><a href="{{ url('/categoria/focos') }}" class="btn-ver"><i class="fa-solid fa-eye"></i> Ver</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- ✅ SCRIPTS DE INTERACCIÓN -->
    <script>
        // Menú de usuario
        const btnUserMenu = document.getElementById('btnUserMenu');
        const userDropdownModal = document.getElementById('userDropdownModal');

        btnUserMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdownModal.style.display = (userDropdownModal.style.display === 'block') ? 'none' : 'block';
        });

        window.addEventListener('click', () => {
            if (userDropdownModal.style.display === 'block') {
                userDropdownModal.style.display = 'none';
            }
        });
    </script>

</body>
</html>