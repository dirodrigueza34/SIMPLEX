<?php
include "conexion.php";

$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$contrasena = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

if (empty($usuario) || empty($contrasena)) {
    header("Location: login.php?error=campos_vacios");
    exit();
}

if (($usuario === 'MAGVD12' && $contrasena === 'cali123') || ($usuario === 'Andres1' && $contrasena === 'simplex2026')) {
    session_start();
    $_SESSION['usuario'] = $usuario;
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=datos_incorrectos");
    exit();
}
?>

