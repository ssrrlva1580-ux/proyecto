<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Sistema Elinoe</title>
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
        
        /* CUADRO EN BLANCO PARA CONFIGURACIÓN */
        .card-blanca { background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); padding: 40px; text-align: center; color: #777; border: 2px dashed #ccc; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="fa-solid fa-bolt"></i><span>Sistema Elinoe</span></div>
        <ul class="sidebar-menu">
            <li><a href="/dashboard"><i class="fa-solid fa-house"></i> Inicio</a></li>
            <li><a href="#"><i class="fa-solid fa-users"></i> Clientes</a></li>
            <li><a href="/proveedores"><i class="fa-solid fa-building"></i> Proveedores</a></li>
            <li><a href="/productos"><i class="fa-solid fa-box-open"></i> Productos</a></li>
            <li><a href="/inventario"><i class="fa-solid fa-boxes-stacked"></i> Inventario</a></li>
            <li class="active"><a href="/configuracion"><i class="fa-solid fa-gear"></i> Configuracion</a></li>
            <li><a href="{{ url('/reportes') }}"><i class="fa-solid fa-chart-line"></i> Reportes</a></li>
            <li><li><a href="{{ url('/usuarios') }}"><i class="fa-solid fa-user-shield"></i> Usuarios</a></li>
        </ul>
        <a href="/logout" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Cerrar Sesión</a>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="menu-toggle"><i class="fa-solid fa-bars"></i></div>
            <div class="user-info">angel <i class="fa-solid fa-circle-user" style="font-size: 24px; color: #1976d2;"></i></div>
        </header>

        <main class="content-area">
            <div class="card-blanca">
                <h2>Sección de Configuración</h2>
                <p style="margin-top: 10px;">Esta página está actualmente en blanco y lista para añadir módulos de personalización.</p>
            </div>
        </main>
    </div>

</body>
</html>