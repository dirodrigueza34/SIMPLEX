<?php
include "conexion.php";

$user = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$pass = isset($_POST['contrasena']) ? trim($_POST['contrasena']) : '';

if (($user === "MAGVD12" && $pass === "cali123") || ($user === "INSTRUCTOR" && $pass === "sena2026")) {
    header("Location: productos.php");
    exit();
} else {
    header("Location: index.php?error=invalido");
    exit();
}
?>




