<?php
$mensaje = "";
// Capturar alertas de error si el login falla en las pruebas
if (isset($_GET['error']) && $_GET['error'] == 'invalido') {
    $mensaje = "<div class='alert alert-error' style='max-width:350px; margin:10px auto;'>Usuario o contraseña incorrectos.</div>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema SIMPLEX - Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
       
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
        }
        .card-login {
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            padding: 35px;
            box-sizing: border-box;
        }
        .card-login h2 {
            font-size: 24px;
            margin-bottom: 25px;
        }
        .form-group-login {
            display: flex;
            flex-direction: column;
            margin-bottom: 18px;
        }
        .form-group-login label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
            color: #000000;
        }
        .form-group-login input {
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            font-size: 14px;
            height: 40px;
            box-sizing: border-box;
            font-family: 'Times New Roman', Times, serif;
        }
        .btn-login {
            background-color: #002b5c;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            width: 100%;
            font-family: 'Times New Roman', Times, serif;
            transition: background-color 0.2s;
        }
        .btn-login:hover {
            background-color: #001f42;
        }
    </style>
</head>
<body>

   
    <nav class="navbar">
        <span class="nav-welcome">¡Bienvenido a SIMPLEX!</span>
        <div class="nav-links">
            <a href="index.php">Inicio</a>
            <a href="#">Usuario</a>
            <a href="#">Ajustes</a>
        </div>
    </nav>

    <?php echo $mensaje; ?>

    <div class="login-wrapper">
        <div class="card-login">
            <h2>Iniciar Sesión</h2>
            
          
            <form action="login_proceso.php" method="POST">
                <div class="form-group-login">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" placeholder="Ej: MAGVD12" required>
                </div>

                <div class="form-group-login">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="cali123" required>
                </div>

                <button type="submit" class="btn-login">Ingresar</button>
            </form>
        </div>
    </div>

</body>
</html>
