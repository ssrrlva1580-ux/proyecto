<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema Elinoe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        
        .login-container { background-color: #ffffff; width: 100%; max-width: 400px; padding: 35px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
        
        .login-header { text-align: center; margin-bottom: 25px; }
        .login-header i { font-size: 35px; color: #1976d2; margin-bottom: 10px; }
        .login-header h2 { color: #333; font-size: 24px; font-weight: 600; }
        .login-header p { color: #666; font-size: 14px; margin-top: 5px; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #444; font-size: 14px; font-weight: 600; }
        
        .form-control { width: 100%; height: 42px; padding: 10px 15px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; transition: border-color 0.3s; outline: none; }
        .form-control:focus { border-color: #1976d2; }
        
        .password-wrapper { position: relative; display: flex; align-items: center; }
        .password-wrapper .form-control { padding-right: 45px; }
        
        .btn-toggle-pwd { position: absolute; right: 5px; height: 100%; width: 35px; background: none; border: none; cursor: pointer; color: #1976d2; font-size: 16px; display: flex; align-items: center; justify-content: center; }
        .btn-toggle-pwd:hover { color: #1565c0; }
        
        .btn-submit { width: 100%; background-color: #1976d2; color: white; border: none; height: 45px; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; transition: background-color 0.3s; margin-top: 10px; }
        .btn-submit:hover { background-color: #1565c0; }
        
        .register-link { text-align: center; margin-top: 20px; font-size: 14px; }
        .register-link a { color: #1976d2; text-decoration: none; font-weight: 600; }
        .register-link a:hover { text-decoration: underline; }

        /* Alertas de éxito y error */
        .alert { padding: 12px; margin-bottom: 20px; border-radius: 4px; text-align: center; font-size: 14px; font-weight: 600; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-header">
            <i class="fa-solid fa-bolt"></i>
            <h2>Sistema Elinoe</h2>
            <p>Inicia sesión para continuar</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST" autocomplete="off">
            @csrf

            <div class="form-group">
                <label for="email">Usuario o Correo Electrónico</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="Ej. admin" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required autocomplete="new-password">
                    <button type="button" class="btn-toggle-pwd" onclick="togglePasswordVisibility('password', 'eye-icon-pass')">
                        <i class="fa-solid fa-eye" id="eye-icon-pass"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Ingresar</button>
        </form>

        <div class="register-link">
            ¿No tienes cuenta? <a href="{{ url('/registro') }}">Regístrate aquí</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>

</body>
</html>