<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\JornadaController;      // ✅ NUEVO IMPORT
use App\Http\Controllers\UsuarioController;
use App\Models\Producto;
use App\Http\Controllers\CalculadoraController;

/*
|--------------------------------------------------------------------------
| 1. RUTA RAÍZ (Página de entrada)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| 2. RUTAS DE AUTENTICACIÓN (Registro y Login)
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    // 1. Intento con credenciales de base de datos (por email o username)
    $loginValue = $request->email;
    $password = $request->password;
    
    $loginType = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    if (Auth::attempt([$loginType => $loginValue, 'password' => $password])) {
        $request->session()->regenerate();
        return redirect('/dashboard');
    }

    // 2. Intento de fallback con credenciales en sesión
    $registeredUser = session('registered_username');
    $registeredEmail = session('registered_email');
    $registeredPass = session('registered_password');

    if (($loginValue === $registeredUser || $loginValue === $registeredEmail) && $password === $registeredPass && !empty($registeredPass)) {
        return redirect('/dashboard');
    }

    return redirect('/login')->with('error', 'Usuario o contraseña incorrectos.');
});

Route::get('/registro', function () {
    return view('registro'); 
})->name('registro.create'); 

Route::post('/registro', [UsuarioController::class, 'store'])->name('registro.store');

Route::delete('/usuario/eliminar', [UsuarioController::class, 'destroy'])->name('usuario.eliminar');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    session()->forget(['registered_name', 'registered_email', 'registered_username', 'registered_password']);
    return redirect('/login');
});


/*
|--------------------------------------------------------------------------
| 3. RUTAS DEL SISTEMA PRINCIPAL (Módulo de Gestión)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    $cables = Producto::where('categoria', 'cables')->count();
    $tubos = Producto::where('categoria', 'tubos')->count();
    $lamparas = Producto::where('categoria', 'lamparas')->count();
    $sockets = Producto::where('categoria', 'sockets')->count();
    $interruptores = Producto::where('categoria', 'interruptores')->count();
    $cintas = Producto::where('categoria', 'cintas-aislantes')->count();
    $cableductos = Producto::where('categoria', 'cableductos')->count();
    $focos = Producto::where('categoria', 'focos')->count();

    return view('dashboard', compact(
        'cables', 'tubos', 'lamparas', 'sockets', 
        'interruptores', 'cintas', 'cableductos', 'focos'
    ));
});

// ✅ RUTAS PARA GUARDAR Y ACTUALIZAR PRODUCTOS
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::put('/productos/{id}', [ProductoController::class, 'update'])->name('productos.update');

// ✅ RUTA PARA GUARDAR CABLES DESDE SU MODAL
Route::post('/categoria/cables', [ProductoController::class, 'storeCable'])->name('cables.store');

// ✅ RUTA PARA RETIRAR STOCK (eliminar cantidad específica)
Route::post('/productos/retirar', [ProductoController::class, 'retirar'])->name('productos.retirar');

Route::get('/proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');
Route::post('/proveedores', [ProveedorController::class, 'store'])->name('proveedores.store');
Route::delete('/proveedores/{id}', [ProveedorController::class, 'destroy'])->name('proveedores.destroy');

Route::get('/configuracion', function () { return view('configuracion'); });

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroyUser'])->name('usuarios.destroy');
Route::get('/reportes', [InventarioController::class, 'reportes'])->name('reportes.index');

Route::get('/clientes', function () {
    return view('clientes');
})->name('clientes.index');

Route::get('/calculadora', [CalculadoraController::class, 'index'])->name('calculadora.index');

/*
|--------------------------------------------------------------------------
| 4. RUTAS DE CLASIFICACIÓN DE MATERIALES (Inventario a detalle)
|--------------------------------------------------------------------------
*/

Route::get('/categoria/cables', function () { 
    $productos = Producto::where('categoria', 'cables')->get();
    return view('categoria_cables', compact('productos')); 
})->name('categoria.cables');

Route::get('/categoria/tubos', function () { 
    $productos = Producto::where('categoria', 'tubos')->get();
    return view('categoria_tubos', compact('productos')); 
})->name('categoria.tubos');

Route::get('/categoria/cableductos', function () { 
    $productos = Producto::where('categoria', 'cableductos')->get();
    return view('categoria_cableductos', compact('productos')); 
})->name('categoria.cableductos');

Route::get('/categoria/lamparas', function () { 
    $productos = Producto::where('categoria', 'lamparas')->get();
    return view('categoria_lamparas', compact('productos')); 
})->name('categoria.lamparas');

Route::get('/categoria/focos', function () { 
    $productos = Producto::where('categoria', 'focos')->get();
    return view('categoria_focos', compact('productos')); 
})->name('categoria.focos');

Route::get('/categoria/sockets', function () { 
    $productos = Producto::where('categoria', 'sockets')->get();
    return view('categoria_sockets', compact('productos')); 
})->name('categoria.sockets');

Route::get('/categoria/interruptores', function () { 
    $productos = Producto::where('categoria', 'interruptores')->get();
    return view('categoria_interruptores', compact('productos')); 
})->name('categoria.interruptores');

Route::get('/categoria/cintas-aislantes', function () { 
    $productos = Producto::where('categoria', 'cintas-aislantes')->get();
    return view('categoria_cintas_aislantes', compact('productos')); 
})->name('categoria.cintas-aislantes');

// ✅ RUTA PARA ELIMINAR PRODUCTO COMPLETO DESDE SU CATEGORÍA
Route::delete('/productos/{id}', [ProductoController::class, 'eliminar'])->name('productos.eliminar');


/*
|--------------------------------------------------------------------------
| 5. RUTAS DEL MÓDULO DE INVENTARIO POR JORNADAS
|--------------------------------------------------------------------------
| Este módulo permite registrar las ventas diarias organizadas por
| fecha, mes y año. Cada día constituye una "jornada" que puede
| abrirse, registrar ventas y cerrarse.
|--------------------------------------------------------------------------
*/

// ✅ Pantalla principal del inventario (lista de jornadas + abrir nueva)
Route::get('/inventario', [JornadaController::class, 'index'])->name('inventario.index');

// ✅ Abrir una nueva jornada
Route::post('/inventario/jornada/abrir', [JornadaController::class, 'abrirJornada'])->name('jornada.abrir');

// ✅ Ver detalle de una jornada específica
Route::get('/inventario/jornada/{id}', [JornadaController::class, 'verJornada'])->name('jornada.ver');

// ✅ Registrar una venta dentro de una jornada
Route::post('/inventario/venta', [JornadaController::class, 'registrarVenta'])->name('venta.registrar');

// ✅ Eliminar una venta (solo si la jornada está abierta)
Route::delete('/inventario/venta/{id}', [JornadaController::class, 'eliminarVenta'])->name('venta.eliminar');

// ✅ Cerrar una jornada (bloquea modificaciones)
Route::post('/inventario/jornada/{id}/cerrar', [JornadaController::class, 'cerrarJornada'])->name('jornada.cerrar');

// ✅ Historial de jornadas agrupadas por mes y año
Route::get('/inventario/historial', [JornadaController::class, 'historial'])->name('jornada.historial');