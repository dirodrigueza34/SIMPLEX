<?php
// Se capturan los datos enviados de forma segura
$user = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$pass = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

// Credenciales base de prueba según tu captura (Usuario: MAGVD12)
if ($user === "MAGVD12" && $pass === "cali123") {
    // Si es correcto, redirige al menú general de la aplicación (productos.php)
    header("Location: productos.php");
    exit();
} else {
    // Si falla, devuelve a la pantalla inicial informando el error de validación
    header("Location: index.php?error=invalido");
    exit();
}
?>


