<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f6f9; padding: 20px; }
        
        .register-card { background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        .logo-container { text-align: center; margin-bottom: 15px; color: #1976d2; font-size: 28px; }
        .register-card h2 { text-align: center; color: #333; font-size: 22px; margin-bottom: 5px; }
        .register-card p.subtitle { text-align: center; color: #666; font-size: 13px; margin-bottom: 25px; }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 5px; }
        .input-container { position: relative; }
        .form-group input { width: 100%; padding: 10px 12px; font-size: 14px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none; transition: border-color 0.3s; }
        .form-group input:focus { border-color: #1976d2; }
        
        .toggle-password { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b; font-size: 14px; }

        .btn-submit { width: 100%; background-color: #1976d2; color: white; border: none; padding: 12px; border-radius: 6px; font-size: 15px; font-weight: bold; cursor: pointer; transition: background 0.3s; margin-top: 10px; }
        .btn-submit:hover { background-color: #1565c0; }

        .login-link { text-align: center; margin-top: 20px; font-size: 13px; color: #666; }
        .login-link a { color: #1976d2; text-decoration: none; font-weight: 600; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="logo-container">
            <i class="fa-solid fa-bolt"></i>
        </div>
        <h2>Registrar Usuario</h2>
        <p class="subtitle">Crea una cuenta para acceder al Sistema Elinoe</p>

        <form action="{{ route('registro.store') }}" method="POST">
            @csrf

            <!-- 1. Nombre Completo -->
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" placeholder="Ej. Ángel Pérez" required>
            </div>

            <!-- 2. Correo Electrónico (Obligatorio) -->
            <div class="form-group">
                <label>Correo Electrónico <span style="color: #dc3545;">*</span></label>
                <input type="email" name="email" placeholder="correo@ejemplo.com" required>
            </div>

            <!-- 3. Registrar Usuario -->
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" placeholder="Ingrese su nombre de usuario" required>
            </div>

            <!-- 4. Confirmar Usuario -->
            <div class="form-group">
                <label>Confirmar Usuario</label>
                <input type="text" name="username_confirmation" placeholder="Confirme su nombre de usuario" required>
            </div>

            <!-- 5. Contraseña -->
            <div class="form-group">
                <label>Contraseña</label>
                <div class="input-container">
                    <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                </div>
            </div>

            <!-- 6. Confirmar Contraseña -->
            <div class="form-group">
                <label>Confirmar Contraseña</label>
                <div class="input-container">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirme su contraseña" required>
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">Registrar Cuenta</button>
        </form>

        <div class="login-link">
            ¿Ya tienes una cuenta? <a href="{{ route('login') }}">Inicia Sesión aquí</a>
        </div>
    </div>

    <!-- Script para alternar la visibilidad de las contraseñas -->
    <script>
        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>